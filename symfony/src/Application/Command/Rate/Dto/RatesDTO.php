<?php

declare(strict_types=1);

namespace App\Application\Command\Rate\Dto;

final readonly class RatesDTO
{
    /**
     * @param RateDTO[] $rates
     */
    public function __construct(
        public \DateTimeImmutable $tradingDate,
        public array $rates
    ) {
    }
}
