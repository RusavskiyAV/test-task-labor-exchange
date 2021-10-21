<?php

declare(strict_types=1);

namespace App\Model\Worker\Entity;

use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class Characteristics
{
    private bool $sex;
    private int $age;
    private float $weight;
    private ?string $location;
    private ?string $description;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        bool $sex,
        int $age,
        float $weight,
        ?string $location = null,
        ?string $description = null
    ) {
        Assert::greaterThan($age, 0);
        Assert::greaterThan($weight, 0);

        if (null !== $location) {
            Assert::notEmpty($location);
        }
        if (null !== $description) {
            Assert::notEmpty($description);
        }

        $this->sex = $sex;
        $this->age = $age;
        $this->weight = $weight;
        $this->location = $location;
        $this->description = $description;
    }

    public function getSex(): bool
    {
        return $this->sex;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
