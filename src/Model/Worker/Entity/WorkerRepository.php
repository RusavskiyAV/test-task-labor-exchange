<?php

declare(strict_types=1);

namespace App\Model\Worker\Entity;

interface WorkerRepository
{
    public function findById(int $id): AbstractWorker;
}
