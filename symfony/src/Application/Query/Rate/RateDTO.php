<?php

namespace App\Application\Query\Rate;

final readonly class RateDTO
{
    public function __construct(public float $rate, public float $rateDiff)
    {
    }
}