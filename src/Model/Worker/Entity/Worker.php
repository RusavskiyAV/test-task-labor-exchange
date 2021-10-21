<?php

declare(strict_types=1);

namespace App\Model\Worker\Entity;

use Brick\Money\Money;

class Worker extends AbstractWorker
{
    /**
     * @var string
     */
    private const MAX_HOURS_PER_DAY = 'PT16H';

    private Characteristics $characteristics;
    private \DateInterval $maxHoursPerDay;

    /**
     * {@inheritDoc}
     */
    public function __construct(
        ?int $id,
        string $name,
        Money $costPerHour,
        Characteristics $characteristics
    ) {
        parent::__construct($id, $name, $costPerHour);

        $this->characteristics = $characteristics;
        $this->maxHoursPerDay = new \DateInterval(static::MAX_HOURS_PER_DAY);
    }

    public function getCharacteristics(): Characteristics
    {
        return $this->characteristics;
    }

    public function getMaxHoursPerDay(): \DateInterval
    {
        return $this->maxHoursPerDay;
    }
}
