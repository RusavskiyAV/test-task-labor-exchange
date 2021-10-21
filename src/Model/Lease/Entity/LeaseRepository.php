<?php

namespace App\Model\Lease\Entity;

interface LeaseRepository
{
    public function findForWorker(int $id, \DateTimeImmutable $from, \DateTimeImmutable $to): array;
}
