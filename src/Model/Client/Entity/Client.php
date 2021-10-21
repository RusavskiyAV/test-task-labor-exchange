<?php

declare(strict_types=1);

namespace App\Model\Client\Entity;

use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class Client
{
    private ?int $id;
    private string $name;
    private ?Vip $vip;
    private \DateTimeZone $dateTimezone;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(?int $id, string $name, ?Vip $vip, \DateTimeZone $dateTimezone)
    {
        if (null !== $id) {
            Assert::greaterThan($id, 0);
        }

        Assert::notEmpty($name);

        $this->id = $id;
        $this->name = $name;
        $this->vip = $vip;
        $this->dateTimezone = $dateTimezone;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVip(): ?Vip
    {
        return $this->vip;
    }

    public function isVip(): bool
    {
        return null !== $this->vip;
    }

    public function getTimezone(): \DateTimeZone
    {
        return $this->dateTimezone;
    }

    public function isPrivilegedThan(Client $client): bool
    {
        return $this->isVip() && (!$client->isVip() || $this->vip->isPrivilegedThan($client->getVip()));
    }
}
