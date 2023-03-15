<?php

declare(strict_types=1);

namespace App\Application\Query\Rate;

use App\Application\Query\QueryHandlerInterface;
use App\Domain\Entity\Rate;
use App\Domain\Repository\RateRepositoryInterface;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\DateImmutable;

final readonly class GetRateHandler implements QueryHandlerInterface
{
    public function __construct(private RateRepositoryInterface $repository)
    {
    }

    public function __invoke(GetRateQuery $query): RateDTO
    {
        $baseCode = Code::create($query->baseCode);
        $code = Code::create($query->code);
        $date = DateImmutable::createFromMutable($query->date);

        $rate = $this->getRate($code, $baseCode, $date);
        $prevRate = $this->getPrevRate($code, $baseCode, $rate->tradingDate);

        return new RateDTO(
            $rate->value->value,
            !is_null($prevRate) ? $rate->calculateRateDiff($prevRate) : 0.0,
            $rate->nominal->nominal
        );
    }

    private function getRate(Code $code, Code $baseCode, DateImmutable $date): Rate
    {
        try {
            $rate = $this->repository->get($baseCode, $code, $date);
        } catch (\DomainException $e) {
            // todo add custom exception
            $rate = $this->getCrossRate($code, $baseCode, $date);
        }

        return $rate;
    }

    private function getCrossRate(Code $code, Code $baseCode, DateImmutable $date): Rate
    {
        $rate = $this->repository->get($code, Code::create(Code::RUR_CODE), $date);
        $baseRate = $this->repository->get($baseCode, Code::create(Code::RUR_CODE), $date);

        return $rate->calculateCrossRate($baseRate);
    }

    private function getPrevRate(Code $code, Code $baseCode, DateImmutable $date): ?Rate
    {
        try {
            $prevDate = $this->repository->getPrevTradingDate($date);
            return $this->getRate($code, $baseCode, $prevDate);
        } catch (\DomainException $e) {
            return null;
        }

    }
}
