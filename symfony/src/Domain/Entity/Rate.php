<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\Nominal;
use App\Domain\ValueObject\Value;
use App\Domain\ValueObject\DateImmutable;
use DateTime;

readonly class Rate
{
    public function __construct(
        public Code          $code,
        public DateImmutable $date,
        public DateImmutable $tradingDate,
        public Value         $value,
        public Nominal       $nominal,
        public Code          $baseCode
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
            Value::create($valuePerRur / $baseValuePerRur),
            Nominal::create(1),
            $baseRate->code
        );
    }

    public function calculateRateDiff(Rate $rate): float
    {
        if (!$this->code->isEqual($rate->code) || !$this->baseCode->isEqual($rate->baseCode)) {
            throw new \DomainException('Rates must be equal');
        }

        $diff = $this->value->value - $rate->value->value;

        return round($diff, Value::PRECISION);
    }

    /**
     * The date from which we can receive the official rate
     * @see https://www.cbr.ru/currency_base/OldVal/
     */
    public static function getStartTradingDate(): DateTime
    {
        return new DateTime('1992-07-01');
    }

    public static function createRurRate(DateImmutable $date, DateImmutable $tradingDate): Rate
    {
        return new Rate(
            Code::create(Code::RUR_CODE),
            $date,
            $tradingDate,
            Value::create(1.0),
            Nominal::create(1),
            Code::create(Code::RUR_CODE),
        );
    }
}