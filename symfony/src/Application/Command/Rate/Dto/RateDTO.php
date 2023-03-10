<?php

declare(strict_types=1);

namespace App\Application\Command\Rate\Dto;

use App\Domain\ValueObject\Code;

final readonly class RateDTO
{
    public function __construct(
        public string $code,
        public int $nominal,
        public float $value,
        public string $baseCode = Code::RUR_CODE,
    ) {
    }
}
