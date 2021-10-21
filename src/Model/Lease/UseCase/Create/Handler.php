<?php

declare(strict_types=1);

namespace App\Model\Lease\UseCase\Create;

use App\Model\Client\Entity\ClientRepository;
use App\Model\Lease\Entity\Lease;
use App\Model\Lease\Entity\LeaseRepository;
use App\Model\Lease\Entity\Rules;
use App\Model\Lease\UseCase\ValueObject\Response;
use App\Model\Worker\Entity\WorkerRepository;
use Carbon\CarbonImmutable;
use Webmozart\Assert\InvalidArgumentException;

class Handler
{
    private ClientRepository $clientRepository;
    private WorkerRepository $workerRepository;
    private LeaseRepository $leaseRepository;

    public function __construct(
        ClientRepository $clientRepository,
        WorkerRepository $workerRepository,
        LeaseRepository $leaseRepository
    ) {
        $this->clientRepository = $clientRepository;
        $this->workerRepository = $workerRepository;
        $this->leaseRepository = $leaseRepository;
    }

    /**
     * @throws \Exception
     */
    public function handle(Command $command): Response
    {
        $client = $this->clientRepository->findById($command->client_id);
        $worker = $this->workerRepository->findById($command->worker_id);

        $utcTimezone = new \DateTimeZone('UTC');
        $response = new Response();

        try {
            $rules = new Rules(
                (new CarbonImmutable($command->from, $client->getTimezone()))->setTimezone($utcTimezone),
                (new CarbonImmutable($command->to, $client->getTimezone()))->setTimezone($utcTimezone),
                new \DateTimeImmutable('now', $utcTimezone)
            );
            $lease = new Lease(null, $client, $worker, $rules);
        } catch (InvalidArgumentException | \DomainException $exception) {
            $response->addError($exception);

            return $response;
        }

        $worker_leases = $this->leaseRepository->findForWorker(
            $worker->getId(),
            $lease->getPeriod()->getFrom(),
            $lease->getPeriod()->getTo()
        );
        $overlapResponse = $lease->getOverlap($worker_leases);

        if (count($overlapResponse->getDeclined()) && 0 === count($overlapResponse->getErrors())) {
            // Обработка отклоненных аренд
        }

        return $overlapResponse;
    }
}
