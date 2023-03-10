<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\DateImmutable;
use App\Domain\ValueObject\Nominal;
use App\Domain\ValueObject\Value;

readonly class Rate
{
    public function __construct(
        public Code $code,
        public DateImmutable $date,
        public DateImmutable $tradingDate,
        public Value $value,
        public Nominal $nominal,
        public Code $baseCode
    ) {
    }

    public function calculateCrossRate(Rate $baseRate): Rate
    {
        if (!$this->date->isEqual($baseRate->date) || !$this->tradingDate->isEqual($baseRate->tradingDate)) {
            throw new \DomainException('Dates must be equal');
        }

        if (!$this->baseCode->isEqual($baseRate->baseCode)) {
            throw new \DomainException('Base valutes must be equal');
        }

        $valuePerRur = $this->value->value / floatval($this->nominal->nominal);
        $baseValuePerRur = $baseRate->value->value / floatval($baseRate->nominal->nominal);

        // todo increase nominal if cross rate is to low
        return new Rate(
            $this->code,
            $this->date,
            $this->tradingDate,
            Value::create($baseValuePerRur / $valuePerRur),
            Nominal::create(1),
            $baseRate->code
        );
    }

    public function calculateRateDiff(Rate $rate): float
    {
        if (!$this->code->isEqual($rate->code) || !$this->baseCode->isEqual($rate->baseCode)) {
            throw new \DomainException('Rates must be equal');
        }

        if (!$this->nominal->isEqual($rate->nominal)) {
            throw new \DomainException('Nominals must be equal');
        }

        $diff = $this->value->value - $rate->value->value;

        return round($diff, Value::PRECISION);
    }

    /**
     * The date from which we can receive the official rate.
     *
     * @see https://www.cbr.ru/currency_base/OldVal/
     */
    public static function getStartTradingDate(): \DateTime
    {
        return new \DateTime('1992-07-01');
    }
}
