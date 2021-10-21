<?php

declare(strict_types=1);

namespace App\Model\Lease\Entity;

use Carbon\CarbonImmutable;
use Webmozart\Assert\Assert;

class FuturePeriod extends Period
{

    /**
     * {@inheritDoc}
     */
    public function __construct(CarbonImmutable $from, CarbonImmutable $to, \DateTimeImmutable $creationDateTime)
    {
        Assert::greaterThan($from, $creationDateTime);

        parent::__construct($from, $to);
    }
}
