<?php

namespace App\Application\Command\Dto;

use App\Domain\ValueObject\Code;
use Symfony\Component\Serializer\Annotation\SerializedName;

class RateDTO
{
    public function __construct(
        public string $code,
        public int $nominal,
        public float $value,
        public string $baseCode = Code::RUR_CODE,
    ) {
    }
}