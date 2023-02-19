<?php

namespace App\Domain\ValueObject;

final readonly class Value
{
    public const PRECISION = 4;

    private function __construct(public float $value)
    {
    }

    public static function create(float $value): Value
    {
        if ($value <= 0) {
            throw new \DomainException('Rate value must be positive');
        }

        return new self(round($value, self::PRECISION));
    }
}