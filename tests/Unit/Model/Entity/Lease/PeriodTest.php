<?php

namespace Unit\Model\Entity\Lease;

use App\Model\Lease\Entity\Period;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class PeriodTest extends TestCase
{
    public function testSuccess(): void
    {
        $from = new CarbonImmutable('2021-10-20');
        $to = new CarbonImmutable('2021-10-21');

        $period = new Period($from, $to);

        self::assertEquals($from, $period->getFrom());
        self::assertEquals($to, $period->getTo());
    }

    public function testToEarlierFrom(): void
    {
        $from = new CarbonImmutable('2021-10-21');
        $to = new CarbonImmutable('2021-10-20');

        $this->expectException(InvalidArgumentException::class);

        new Period($from, $to);
    }

    public function testWholeDays(): void
    {
        $period = new Period(
            new CarbonImmutable('2021-10-20T00:00:00'),
            new CarbonImmutable('2021-10-20T01:00:00')
        );
        self::assertEquals(0, $period->getWholeDays());

        $period = new Period(
            new CarbonImmutable('2021-10-20T00:00:00'),
            new CarbonImmutable('2021-10-21T00:00:00')
        );
        self::assertEquals(1, $period->getWholeDays());

        $period = new Period(
            new CarbonImmutable('2021-10-20T05:00:00'),
            new CarbonImmutable('2021-10-23T05:00:00')
        );
        self::assertEquals(2, $period->getWholeDays());
    }

    public function testIncompleteDaysNotSameObjects(): void
    {
        $period = new Period(
            new CarbonImmutable('2021-10-20T00:00:00'),
            new CarbonImmutable('2021-10-20T01:00:00')
        );
        $reflectionClass = new \ReflectionClass($period);

        $this->assertNotSame(
            $reflectionClass->getProperty('incompleteFirstDayInterval'),
            $period->getIncompleteFirstDayInterval()
        );
        $this->assertNotSame(
            $reflectionClass->getProperty('incompleteLastDayInterval'),
            $period->getIncompleteLastDayInterval()
        );
    }

    public function testIncompleteDays(): void
    {
        $emptyInterval = new CarbonInterval(0);
        $oneHourInterval = new CarbonInterval(0, 0, 0, 0, 1);

        $period = new Period(
            new CarbonImmutable('2021-10-20T00:00:00'),
            new CarbonImmutable('2021-10-20T01:00:00')
        );
        self::assertEquals($oneHourInterval, $period->getIncompleteFirstDayInterval());
        self::assertEquals($emptyInterval, $period->getIncompleteLastDayInterval());

        $period = new Period(
            new CarbonImmutable('2021-10-20T00:00:00'),
            new CarbonImmutable('2021-10-21T01:00:00')
        );
        self::assertEquals($emptyInterval, $period->getIncompleteFirstDayInterval());
        self::assertEquals($oneHourInterval, $period->getIncompleteLastDayInterval());

        $period = new Period(
            new CarbonImmutable('2021-10-20T05:00:00'),
            new CarbonImmutable('2021-10-20T07:00:00')
        );
        self::assertEquals(
            new CarbonInterval(
                0,
                0,
                0,
                0,
                2
            ),
            $period->getIncompleteFirstDayInterval()
        );
        self::assertEquals($emptyInterval, $period->getIncompleteLastDayInterval());

        $period = new Period(
            new CarbonImmutable('2021-10-20T05:00:00'),
            new CarbonImmutable('2021-10-21T07:00:00')
        );
        self::assertEquals(
            new CarbonInterval(
                0,
                0,
                0,
                0,
                19
            ),
            $period->getIncompleteFirstDayInterval()
        );
        self::assertEquals(
            new CarbonInterval(
                0,
                0,
                0,
                0,
                7
            ),
            $period->getIncompleteLastDayInterval()
        );

        $period = new Period(
            new CarbonImmutable('2021-10-20T07:00:00'),
            new CarbonImmutable('2021-10-22T00:00:00')
        );
        self::assertEquals(
            new CarbonInterval(
                0,
                0,
                0,
                0,
                17
            ),
            $period->getIncompleteFirstDayInterval()
        );
        self::assertEquals($emptyInterval, $period->getIncompleteLastDayInterval());
    }
}
