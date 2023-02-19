<?php

namespace App\Application\Query\Rate;

use App\Application\Query\QueryHandlerInterface;
use App\Domain\Entity\Rate;
use App\Domain\Repository\RateRepositoryInterface;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\DateImmutable;
use Exception;

final readonly class GetRateHandler implements QueryHandlerInterface
{
    public function __construct(private RateRepositoryInterface $repository)
    {
    }

    public function __invoke(GetRateQuery $query): ?RateDTO
    {
        $date = DateImmutable::createFromMutable($query->date);
        $code = Code::create($query->code);
        $baseCode = Code::create($query->baseCode);

        $rate = $this->getRate($date, $code, $baseCode);

        $prevTradingDate = $this->repository->getPrevTradingDate($rate, $date);
        if (null !== $prevTradingDate) {
            $prevRate = $this->getRate(
                $prevTradingDate,
                $code,
                $baseCode
            );
        } else {
            $prevRate = null;
        }

        return new RateDTO(
            $rate->value->value,
            $prevRate ? $rate->calculateRateDiff($prevRate) : 0.0,
        );
    }

    private function getRate(DateImmutable $date, Code $code, Code $baseCode): Rate
    {
        $rate = $this->repository->getByCodeAndDate($code, $date);
        if (null === $rate) {
            throw new Exception('Rate not found');
        }

        if ($baseCode->code !== Code::RUR_CODE) {
            $baseRate = $this->repository->getByCodeAndDate($baseCode, $date);
            if (null === $baseRate) {
                throw new Exception('Rate not found');
            }

            return $rate->calculateCrossRate($baseRate);
        }

        return $rate;
    }
}