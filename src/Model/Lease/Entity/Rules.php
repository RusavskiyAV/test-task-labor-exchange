<?php

declare(strict_types=1);

namespace App\Model\Lease\Entity;

use App\Model\Client\Entity\Client;
use App\Model\Lease\UseCase\ValueObject\OverlapResponse;
use App\Model\Worker\Entity\AbstractWorker;
use Brick\Money\Money;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Webmozart\Assert\InvalidArgumentException;

class Rules
{
    private FuturePeriod $workPeriod;
    private FuturePeriod $leasePeriod;
    private \DateTimeImmutable $creationDateTime;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(CarbonImmutable $from, CarbonImmutable $to, \DateTimeImmutable $creationDateTime)
    {
        $this->workPeriod = new FuturePeriod($from->startOfMinute(), $to->startOfMinute(), $creationDateTime);
        $this->leasePeriod = new FuturePeriod($from->startOfHour(), $to->ceilHour(), $creationDateTime);
        $this->creationDateTime = $creationDateTime;
    }

    public function getWorkPeriod(): Period
    {
        return $this->workPeriod;
    }

    public function getLeasePeriod(): Period
    {
        return $this->leasePeriod;
    }

    public function canWorkerHandle(AbstractWorker $worker): bool
    {
        return $this->canWorkerHandleIncompleteDay($worker, $this->workPeriod->getIncompleteFirstDayInterval())
            && $this->canWorkerHandleIncompleteDay($worker, $this->workPeriod->getIncompleteLastDayInterval());
    }

    public function calculatePrice(Money $costPerHour, \DateInterval $maxHoursPerDay): Money
    {
        return $costPerHour->multipliedBy($this->leasePeriod->getWholeDays() * $maxHoursPerDay->h)
            ->plus($costPerHour->multipliedBy($this->leasePeriod->getIncompleteFirstDayInterval()->hours))
            ->plus($costPerHour->multipliedBy($this->leasePeriod->getIncompleteLastDayInterval()->hours));
    }

    public function getOverlap(Client $client, AbstractWorker $worker, array $leases): OverlapResponse
    {
        $response = new OverlapResponse();
        $firstDayInterval = $this->workPeriod->getIncompleteFirstDayInterval();
        $lastDayInterval = $this->workPeriod->getIncompleteLastDayInterval();

        /** @var Lease $lease */
        foreach ($leases as $lease) {
            if (null === $overlapPeriod = $this->leasePeriod->getOverlap($lease->getPeriod())) {
                if ($this->workPeriod->getFrom()->isSameDay($lease->getWorkPeriod()->getTo())) {
                    $firstDayInterval->add($lease->getWorkPeriod()->getIncompleteLastDayInterval());
                } elseif ($this->workPeriod->getTo()->isSameDay($lease->getWorkPeriod()->getFrom())) {
                    $lastDayInterval->add($lease->getWorkPeriod()->getIncompleteFirstDayInterval());
                }
            } elseif (
                $client->isPrivilegedThan($lease->getClient())
                && $lease->getPeriod()->getFrom()->greaterThan($this->creationDateTime)
            ) {
                $response->addDecline($lease);
            } else {
                $response->addError(
                    new \DomainException(
                        sprintf('Worker is busy from %s to %s', $overlapPeriod->getFrom(), $overlapPeriod->getTo())
                    )
                );
            }
        }

        if (!$this->canWorkerHandleIncompleteDay($worker, $firstDayInterval)) {
            $response->addError(
                new \DomainException(
                    sprintf(
                        'Overworked %s for %s',
                        $this->workPeriod->getFrom()->toDateString(),
                        $firstDayInterval->sub($worker->getMaxHoursPerDay())
                    )
                )
            );
        }
        if (!$this->canWorkerHandleIncompleteDay($worker, $lastDayInterval)) {
            $response->addError(
                new \DomainException(
                    sprintf(
                        'Overworked %s for %s',
                        $this->workPeriod->getTo()->toDateString(),
                        $lastDayInterval->sub($worker->getMaxHoursPerDay())
                    )
                )
            );
        }

        return $response;
    }

    private function canWorkerHandleIncompleteDay(AbstractWorker $worker, CarbonInterval $incompleteDayInterval): bool
    {
        return !$incompleteDayInterval->greaterThan($worker->getMaxHoursPerDay());
    }
}
