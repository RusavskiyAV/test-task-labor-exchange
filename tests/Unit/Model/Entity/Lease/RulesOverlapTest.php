<?php

namespace Unit\Model\Entity\Lease;

use App\Model\Client\Entity\Client;
use App\Model\Client\Entity\Vip;
use App\Model\Lease\Entity\Lease;
use App\Model\Lease\Entity\Rules;
use App\Model\Worker\Entity\Worker;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use Tests\Builder\ClientBuilder;
use Tests\Builder\WorkerBuilder;

class RulesOverlapTest extends TestCase
{
    private Client $vipClient;
    private Client $client;
    private Worker $worker;
    private \DateTimeImmutable $dateTime;

    public function setUp(): void
    {
        $this->dateTime = new \DateTimeImmutable('2021-01-01');
        $this->vipClient = (new ClientBuilder())->withVip(new Vip())->build();
        $this->client = (new ClientBuilder())->build();
        $this->worker = (new WorkerBuilder())->build();
    }

    public function testNoOverlap(): void
    {
        $rules = new Rules(
            new CarbonImmutable('2021-10-12'),
            new CarbonImmutable('2021-10-13'),
            $this->dateTime
        );
        $response = $rules->getOverlap(
            $this->client,
            $this->worker,
            [
                new Lease(
                    null,
                    $this->client,
                    $this->worker,
                    new Rules(
                        new CarbonImmutable('2021-10-10'),
                        new CarbonImmutable('2021-10-11'),
                        $this->dateTime
                    )
                )
            ]
        );

        $this->assertNull($response->getLease());
        $this->assertEmpty($response->getErrors());
        $this->assertEmpty($response->getDeclined());
    }

    public function testDeclined(): void
    {
        $rules = new Rules(
            new CarbonImmutable('2021-10-11'),
            new CarbonImmutable('2021-10-13'),
            $this->dateTime
        );
        $lease = new Lease(
            null,
            $this->client,
            $this->worker,
            new Rules(
                new CarbonImmutable('2021-10-10'),
                new CarbonImmutable('2021-10-12'),
                $this->dateTime
            )
        );
        $response = $rules->getOverlap(
            $this->vipClient,
            $this->worker,
            [$lease]
        );

        $this->assertEmpty($response->getErrors());
        $this->assertEquals(
            [$lease],
            $response->getDeclined()
        );
    }

    public function testErrors(): void
    {
        $rules = new Rules(
            new CarbonImmutable('2021-10-11'),
            new CarbonImmutable('2021-10-13'),
            $this->dateTime
        );
        $response = $rules->getOverlap(
            $this->client,
            $this->worker,
            [
                new Lease(
                    null,
                    $this->client,
                    $this->worker,
                    new Rules(
                        new CarbonImmutable('2021-10-10'),
                        new CarbonImmutable('2021-10-12'),
                        $this->dateTime
                    )
                )
            ]
        );

        $this->assertEmpty($response->getDeclined());
        $this->assertEquals(
            [new \DomainException('Worker is busy from 2021-10-11 00:00:00 to 2021-10-12 00:00:00')],
            $response->getErrors()
        );
    }

    public function testIncompleteFirstDay(): void
    {
        $rules = new Rules(
            new CarbonImmutable('2021-10-11T16:00:00'),
            new CarbonImmutable('2021-10-13'),
            $this->dateTime
        );
        $response = $rules->getOverlap(
            $this->client,
            $this->worker,
            [
                new Lease(
                    null,
                    $this->client,
                    $this->worker,
                    new Rules(
                        new CarbonImmutable('2021-10-10'),
                        new CarbonImmutable('2021-10-11T15:00:00'),
                        $this->dateTime
                    )
                )
            ]
        );

        $this->assertEmpty($response->getDeclined());
        $this->assertEquals(
            [new \DomainException('Overworked 2021-10-11 for 7 hours')],
            $response->getErrors()
        );
    }

    public function testIncompleteLastDay(): void
    {
        $rules = new Rules(
            new CarbonImmutable('2021-10-11'),
            new CarbonImmutable('2021-10-13T16:00:00'),
            $this->dateTime
        );
        $response = $rules->getOverlap(
            $this->vipClient,
            $this->worker,
            [
                new Lease(
                    null,
                    $this->vipClient,
                    $this->worker,
                    new Rules(
                        new CarbonImmutable('2021-10-13T17:00:00'),
                        new CarbonImmutable('2021-10-14'),
                        $this->dateTime
                    )
                )
            ]
        );

        $this->assertEmpty($response->getDeclined());
        $this->assertEquals(
            [new \DomainException('Overworked 2021-10-13 for 7 hours')],
            $response->getErrors()
        );
    }
}
