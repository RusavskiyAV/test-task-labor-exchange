<?php

namespace Tests\Builder;

use App\Model\Client\Entity\Client;
use App\Model\Client\Entity\Vip;

class ClientBuilder
{
    private $id;
    private $name;
    private $vip;
    private $dateTimezone;

    public function __construct()
    {
        $this->id = 1;
        $this->name = 'Test client name';
        $this->dateTimezone = new \DateTimeZone('UTC');
    }

    public function withVip(Vip $vip): self
    {
        $this->vip = $vip;

        return $this;
    }

    public function build(): Client
    {
        return new Client(
            $this->id,
            $this->name,
            $this->vip,
            $this->dateTimezone
        );
    }
}
