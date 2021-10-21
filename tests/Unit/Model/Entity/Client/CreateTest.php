<?php

namespace Unit\Model\Entity\Client;

use App\Model\Client\Entity\Client;
use App\Model\Client\Entity\Vip;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = 1;
        $name = 'Test name';
        $vip = new Vip();
        $dateTimeZone = new \DateTimeZone('Europe/Moscow');

        $client = new Client($id, $name, $vip, $dateTimeZone);

        self::assertEquals($id, $client->getId());
        self::assertEquals($name, $client->getName());
        self::assertTrue($client->isVip());
        self::assertEquals($dateTimeZone, $client->getTimezone());
    }

    public function testEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Client(
            1,
            '',
            new Vip(),
            new \DateTimeZone('Europe/Moscow')
        );
    }

    public function testNotVip(): void
    {
        $client = new Client(
            1,
            'Test name',
            null,
            new \DateTimeZone('Europe/Moscow')
        );

        self::assertFalse($client->isVip());
    }
}
