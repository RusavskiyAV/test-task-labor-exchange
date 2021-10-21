<?php

declare(strict_types=1);

namespace App\Model\Client\Entity;

class Vip
{
    public function isPrivilegedThan(self $vip): bool
    {
        return false;
    }
}
