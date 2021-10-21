<?php

namespace Unit\Model\Entity\Lease;

use App\Model\Lease\Entity\FuturePeriod;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class FuturePeriodTest extends TestCase
{
    public function testSuccess(): void
    {
        $from = new CarbonImmutable('2021-10-19');
        $to = new CarbonImmutable('2021-10-20');
        $dateTime = new \DateTimeImmutable('2021-10-20');

        $this->expectException(InvalidArgumentException::class);

        new FuturePeriod($from, $to, $dateTime);
    }
}
