<?php

declare(strict_types=1);

namespace App\Model\Client\Entity;

interface ClientRepository
{
    public function findById(int $id): Client;
}
