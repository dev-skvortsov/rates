<?php

declare(strict_types=1);

namespace App\Application\Command\Rate\Dto;

final readonly class RateDTO
{
    public function __construct(
        public string $code,
        public string $baseCode,
        public int $nominal,
        public float $value,
    ) {
    }
}
