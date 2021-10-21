<?php

declare(strict_types=1);

namespace App\Model\Lease\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var int
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    public $worker_id;
    /**
     * @var int
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    public $client_id;
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\DateTime
     */
    public $from;
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\DateTime
     */
    public $to;
}
