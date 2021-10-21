<?php

namespace Unit\Model\Entity\Worker;

use App\Model\Worker\Entity\AbstractWorker;
use Brick\Money\Money;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = 1;
        $name = 'Test name';
        $cost = Money::of(1, 'RUB');

        $worker = $this->getMockForAbstractClass(AbstractWorker::class, [$id, $name, $cost]);

        self::assertEquals($id, $worker->getId());
        self::assertEquals($name, $worker->getName());
        self::assertTrue($cost->isEqualTo($worker->getCostPerHour()));
    }
}
