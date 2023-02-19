<?php

declare(strict_types=1);

namespace App\Application\Command\Rate;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\Rate\Dto\RateDTO;
use App\Domain\Entity\Rate;
use App\Domain\Repository\RateRepositoryInterface;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\DateImmutable;
use App\Domain\ValueObject\Nominal;
use App\Domain\ValueObject\Value;

final readonly class SaveRatesHandler implements CommandHandlerInterface
{
    public function __construct(private RateRepositoryInterface $repository)
    {
    }

    public function __invoke(SaveRatesCommand $command): void
    {
        $rates = array_map(
            function (RateDTO $rate) use ($command) {
                return $this->createRate(
                    $command->date,
                    $command->ratesDTO->tradingDate,
                    $rate
                );
            },
            $command->ratesDTO->rates
        );

        $this->repository->storeMultiple($rates);
    }

    private function createRate(\DateTimeImmutable $date, \DateTimeImmutable $tradingDate, RateDTO $rateDTO): Rate
    {
        return new Rate(
            Code::create($rateDTO->code),
            DateImmutable::createFromDateTimeImmutable($date),
            DateImmutable::createFromDateTimeImmutable($tradingDate),
            Value::create($rateDTO->value),
            Nominal::create($rateDTO->nominal),
            Code::create($rateDTO->baseCode),
        );
    }
}
