<?php

namespace Unit\Model\Entity\Lease;

use App\Model\Lease\Entity\FuturePeriod;
use App\Model\Lease\Entity\Rules;
use App\Model\Worker\Entity\AbstractWorker;
use App\Model\Worker\Entity\Characteristics;
use App\Model\Worker\Entity\Worker;
use Brick\Money\Money;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class RulesTest extends TestCase
{
    private \DateTimeImmutable $dateTime;
    private AbstractWorker $worker;

    public function setUp(): void
    {
        $this->dateTime = new \DateTimeImmutable('2021-10-19');
        $this->worker = new Worker(
            null,
            'Test name',
            Money::of(1, 'RUB'),
            new Characteristics(true, 18, 80)
        );
    }

    public function testSuccess(): void
    {
        $from = new CarbonImmutable('2021-10-20T00:01:59');
        $to = new CarbonImmutable('2021-10-21T01:20:18');

        $rules = new Rules($from, $to, $this->dateTime);

        self::assertEquals(
            new FuturePeriod(
                new CarbonImmutable('2021-10-20T00:01:00'),
                new CarbonImmutable('2021-10-21T01:20:00'),
                $this->dateTime
            ),
            $rules->getWorkPeriod()
        );
        self::assertEquals(
            new FuturePeriod(
                new CarbonImmutable('2021-10-20T00:00:00'),
                new CarbonImmutable('2021-10-21T02:00:00'),
                $this->dateTime
            ),
            $rules->getLeasePeriod()
        );
    }

    public function testWorkerCanHandle(): void
    {
        $rules = new Rules(
            new CarbonImmutable('2021-10-20T00:00:00'),
            new CarbonImmutable('2021-10-20T16:00:00'),
            $this->dateTime
        );
        self::assertTrue($rules->canWorkerHandle($this->worker));

        $rules = new Rules(
            new CarbonImmutable('2021-10-20T00:00:00'),
            new CarbonImmutable('2021-10-20T17:00:00'),
            $this->dateTime
        );
        self::assertFalse($rules->canWorkerHandle($this->worker));

        $rules = new Rules(
            new CarbonImmutable('2021-10-20T07:00:00'),
            new CarbonImmutable('2021-10-22T00:00:00'),
            $this->dateTime
        );
        self::assertFalse($rules->canWorkerHandle($this->worker));
    }

    public function testCalculatePrice(): void
    {
        $rules = new Rules(
            new CarbonImmutable('2021-10-20T00:00:00'),
            new CarbonImmutable('2021-10-20T16:00:00'),
            $this->dateTime
        );
        self::assertEquals(
            Money::of(16, 'RUB'),
            $rules->calculatePrice($this->worker->getCostPerHour(), $this->worker->getMaxHoursPerDay())
        );

        $rules = new Rules(
            new CarbonImmutable('2021-10-20T00:00:00'),
            new CarbonImmutable('2021-10-21T16:00:00'),
            $this->dateTime
        );
        self::assertEquals(
            Money::of(32, 'RUB'),
            $rules->calculatePrice($this->worker->getCostPerHour(), $this->worker->getMaxHoursPerDay())
        );

        $rules = new Rules(
            new CarbonImmutable('2021-10-20T07:00:00'),
            new CarbonImmutable('2021-10-22T03:00:00'),
            $this->dateTime
        );
        self::assertEquals(
            Money::of(36, 'RUB'),
            $rules->calculatePrice($this->worker->getCostPerHour(), $this->worker->getMaxHoursPerDay())
        );
    }
}
