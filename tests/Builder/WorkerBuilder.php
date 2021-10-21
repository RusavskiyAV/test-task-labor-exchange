<?php

namespace Tests\Builder;

use App\Model\Worker\Entity\Characteristics;
use App\Model\Worker\Entity\Worker;
use Brick\Money\Money;

class WorkerBuilder
{
    private $id;
    private $name;
    private $costPerHour;
    private $characteristics;

    public function __construct()
    {
        $this->id = 1;
        $this->name = 'Test worker name';
        $this->costPerHour = Money::of(1, 'RUB');
        $this->characteristics = new Characteristics(true, 18, 80);
    }

    public function build(): Worker
    {
        return new Worker(
            $this->id,
            $this->name,
            $this->costPerHour,
            $this->characteristics
        );
    }
}
