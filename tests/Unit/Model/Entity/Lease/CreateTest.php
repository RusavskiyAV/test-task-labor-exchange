<?php

namespace Unit\Model\Entity\Lease;

use App\Model\Lease\Entity\Lease;
use App\Model\Lease\Entity\Rules;
use Tests\Builder\ClientBuilder;
use Tests\Builder\WorkerBuilder;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $client = (new ClientBuilder())->build();
        $worker = (new WorkerBuilder())->build();
        $dateTime = new \DateTimeImmutable('2021-10-09T00:00:00');
        $rules = new Rules(
            new CarbonImmutable('2021-10-10T00:00:00'),
            new CarbonImmutable('2021-10-10T08:00:00'),
            $dateTime
        );
        $lease = new Lease(null, $client, $worker, $rules);

        $this->assertEquals($client, $lease->getClient());
        $this->assertEquals($worker, $lease->getWorker());
        $this->assertEquals(
            $rules->calculatePrice($worker->getCostPerHour(), $worker->getMaxHoursPerDay()),
            $lease->getPrice()
        );
        $this->assertEquals($rules->getLeasePeriod(), $lease->getPeriod());
        $this->assertEquals($rules->getWorkPeriod(), $lease->getWorkPeriod());
    }
}
