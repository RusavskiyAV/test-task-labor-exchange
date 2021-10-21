<?php

namespace Unit\Model\Entity\Client;

use App\Model\Client\Entity\Vip;
use PHPUnit\Framework\TestCase;
use Tests\Builder\ClientBuilder;

class PrivilegeTest extends TestCase
{
    public function testSuccess(): void
    {
        $vipClient = (new ClientBuilder())->withVip(new Vip())->build();
        $client1 = (new ClientBuilder())->build();
        $client2 = (new ClientBuilder())->build();

        $this->assertTrue($vipClient->isPrivilegedThan($client1));
        $this->assertFalse($client1->isPrivilegedThan($client2));
    }
}
