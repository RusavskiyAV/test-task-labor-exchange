<?php

namespace Unit\Model\Entity\Worker;

use App\Model\Worker\Entity\Characteristics;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class CharacteristicsTest extends TestCase
{
    private bool $sex;
    private int $age;
    private float $weight;

    public function setUp(): void
    {
        $this->sex = true;
        $this->age = 18;
        $this->weight = 80;
    }

    public function testSuccess(): void
    {
        $characteristics = new Characteristics($this->sex, $this->age, $this->weight);

        self::assertEquals($this->sex, $characteristics->getSex());
        self::assertEquals($this->age, $characteristics->getAge());
        self::assertEquals($this->weight, $characteristics->getWeight());
        self::assertNull($characteristics->getLocation());
        self::assertNull($characteristics->getDescription());
    }

    public function testEmptyLocation(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Characteristics($this->sex, $this->age, $this->weight, '');
    }

    public function testEmptyDescription(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Characteristics($this->sex, $this->age, $this->weight, 'Test location', '');
    }

    public function testMinAge(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Characteristics($this->sex, 0, $this->weight);
    }

    public function testMinWeight(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Characteristics($this->sex, $this->age, 0);
    }
}
