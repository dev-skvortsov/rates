<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Rate;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\DateImmutable;

interface RateRepositoryInterface
{
    /**
     * @param Rate[] $rates
     */
    public function storeMultiple(array $rates): void;

    /**
     * @throws \DomainException
     */
    public function get(Code $baseCode, Code $code, DateImmutable $date): Rate;

    /**
     * @throws \DomainException
     */
    public function getPrevTradingDate(DateImmutable $date): DateImmutable;
}
