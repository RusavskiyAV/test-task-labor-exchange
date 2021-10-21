<?php

declare(strict_types=1);

namespace App\Model\Lease\Entity;

use App\Model\Client\Entity\Client;
use App\Model\Lease\UseCase\ValueObject\OverlapResponse;
use App\Model\Worker\Entity\AbstractWorker;
use Brick\Money\Money;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class Lease
{
    private ?int $id;
    private Client $client;
    private AbstractWorker $worker;
    private Rules $rules;
    private Money $price;

    /**
     * @throws \DomainException
     * @throws InvalidArgumentException
     */
    public function __construct(
        ?int $id,
        Client $client,
        AbstractWorker $worker,
        Rules $rules
    ) {
        if (null !== $id) {
            Assert::greaterThan($id, 0);
        }

        if (!$rules->canWorkerHandle($worker)) {
            throw new \DomainException('Переработка');
        }

        $this->id = $id;
        $this->client = $client;
        $this->worker = $worker;
        $this->rules = $rules;
        $this->price = $rules->calculatePrice($worker->getCostPerHour(), $worker->getMaxHoursPerDay());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getWorker(): AbstractWorker
    {
        return $this->worker;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getPeriod(): Period
    {
        return $this->rules->getLeasePeriod();
    }

    public function getWorkPeriod(): Period
    {
        return $this->rules->getWorkPeriod();
    }

    /**
     * @param self[]
     */
    public function getOverlap(array $leases): OverlapResponse
    {
        return $this->rules->getOverlap($this->client, $this->worker, $leases)->setLease($this);
    }
}
