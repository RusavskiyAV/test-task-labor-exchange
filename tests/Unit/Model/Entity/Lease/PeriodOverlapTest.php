<?php

namespace Unit\Model\Entity\Lease;

use App\Model\Lease\Entity\Period;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class PeriodOverlapTest extends TestCase
{
    private Period $period;

    public function setUp(): void
    {
        $this->period = new Period(
            new CarbonImmutable('2021-10-10'),
            new CarbonImmutable('2021-10-20')
        );
    }

    public function testNoOverlap(): void
    {
        $period = new Period(
            new CarbonImmutable('2021-10-01'),
            new CarbonImmutable('2021-10-02')
        );

        $this->assertNull($period->getOverlap($this->period));
    }

    public function testOverlapInner(): void
    {
        $period = new Period(
            new CarbonImmutable('2021-10-11'),
            new CarbonImmutable('2021-10-13')
        );

        $this->assertEquals($period, $period->getOverlap($this->period));
    }

    public function testOverlapOuter(): void
    {
        $period = new Period(
            new CarbonImmutable('2021-10-09'),
            new CarbonImmutable('2021-10-21')
        );

        $this->assertEquals($this->period, $period->getOverlap($this->period));
    }

    public function testOverlapBefore(): void
    {
        $period = new Period(
            new CarbonImmutable('2021-10-09'),
            new CarbonImmutable('2021-10-15')
        );

        $this->assertEquals(
            new Period(
                new CarbonImmutable('2021-10-10'),
                new CarbonImmutable('2021-10-15')
            ),
            $period->getOverlap($this->period)
        );
    }

    public function testOverlapAfter(): void
    {
        $period = new Period(
            new CarbonImmutable('2021-10-15'),
            new CarbonImmutable('2021-10-25')
        );

        $this->assertEquals(
            new Period(
                new CarbonImmutable('2021-10-15'),
                new CarbonImmutable('2021-10-20')
            ),
            $period->getOverlap($this->period)
        );
    }
}
