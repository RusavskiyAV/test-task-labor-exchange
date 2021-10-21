<?php

declare(strict_types=1);

namespace App\Model\Lease\UseCase\ValueObject;

use App\Model\Lease\Entity\Lease;

class OverlapResponse extends Response
{
    /**
     * @var Lease[]
     */
    private array $declined;

    public function __construct(?Lease $lease = null)
    {
        $this->declined = [];

        parent::__construct($lease);
    }

    public function addDecline(Lease $lease): void
    {
        $this->declined[] = $lease;
    }

    public function getDeclined(): array
    {
        return $this->declined;
    }
}
