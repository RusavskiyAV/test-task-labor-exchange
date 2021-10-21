<?php

declare(strict_types=1);

namespace App\Model\Lease\UseCase\ValueObject;

use App\Model\Lease\Entity\Lease;

class Response
{
    private ?Lease $lease;
    /**
     * @var \Exception[]
     */
    private array $errors;

    public function __construct(?Lease $lease = null)
    {
        $this->lease = $lease;
        $this->errors = [];
    }

    public function getLease(): ?Lease
    {
        return $this->lease;
    }

    public function setLease(Lease $lease): self
    {
        $this->lease = $lease;

        return $this;
    }

    public function addError(\Exception $exception): void
    {
        $this->errors[] = $exception;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
