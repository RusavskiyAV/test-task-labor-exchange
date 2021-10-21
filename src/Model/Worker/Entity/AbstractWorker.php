<?php

declare(strict_types=1);

namespace App\Model\Worker\Entity;

use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Money;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

abstract class AbstractWorker
{
    private ?int $id;
    private string $name;
    private Money $costPerHour;

    /**
     * @throws MoneyMismatchException
     * @throws InvalidArgumentException
     */
    public function __construct(?int $id, string $name, Money $costPerHour)
    {
        if (null !== $id) {
            Assert::greaterThan($id, 0);
        }

        Assert::notEmpty($name);
        Assert::true($costPerHour->isGreaterThan(0));

        $this->id = $id;
        $this->name = $name;
        $this->costPerHour = $costPerHour;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCostPerHour(): Money
    {
        return $this->costPerHour;
    }

    abstract public function getMaxHoursPerDay(): \DateInterval;
}
