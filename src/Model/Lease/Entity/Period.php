<?php

declare(strict_types=1);

namespace App\Model\Lease\Entity;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class Period
{
    private CarbonImmutable $from;
    private CarbonImmutable $to;
    private int $whole_days;
    private CarbonInterval $incompleteFirstDayInterval;
    private CarbonInterval $incompleteLastDayInterval;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(CarbonImmutable $from, CarbonImmutable $to)
    {
        Assert::greaterThan($to, $from);

        $this->from = $from;
        $this->to = $to;
        $this->incompleteFirstDayInterval = new CarbonInterval(0);
        $this->incompleteLastDayInterval = new CarbonInterval(0);
        $this->whole_days = (new CarbonPeriod($from->toDateString(), $to->toDateString()))
            ->excludeStartDate()
            ->excludeEndDate()
            ->count();

        if ($from->isStartOfDay() && !$from->isSameDay($to)) {
            $this->whole_days++;
        }

        if ($from->isSameDay($to)) {
            $this->incompleteFirstDayInterval->add($from->diff($to));
        } else {
            $this->incompleteLastDayInterval->add($to->startOfDay()->diff($to));

            if (!$from->isStartOfDay()) {
                $this->incompleteFirstDayInterval->add($from->diff($from->toMutable()->addDay()->startOfDay()));
            }
        }
    }

    public function getFrom(): CarbonImmutable
    {
        return $this->from;
    }

    public function getTo(): CarbonImmutable
    {
        return $this->to;
    }

    public function getWholeDays(): int
    {
        return $this->whole_days;
    }

    public function getIncompleteFirstDayInterval(): CarbonInterval
    {
        return $this->incompleteFirstDayInterval->copy();
    }

    public function getIncompleteLastDayInterval(): CarbonInterval
    {
        return $this->incompleteLastDayInterval->copy();
    }

    public function getOverlap(Period $period): ?Period
    {
        $overlapFunc = static function (Period $period1, Period $period2): ?Period {
            if ($period1->from->isBetween($period2->from, $period2->to, false)) {
                if ($period1->to->isBetween($period2->from, $period2->to, false)) {
                    return $period1;
                }

                return new Period($period1->from, $period2->to);
            }

            return null;
        };

        if (null !== $overlapPeriod = $overlapFunc($this, $period)) {
            return $overlapPeriod;
        }

        return $overlapFunc($period, $this);
    }
}
