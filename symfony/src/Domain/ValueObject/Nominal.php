<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final readonly class Nominal
{
    private function __construct(public int $nominal)
    {
    }

    public static function create(int $nominal): Nominal
    {
        if ($nominal < 1) {
            throw new \DomainException('Nominal must be positive');
        }

        return new self($nominal);
    }

    public function isEqual(Nominal $nominal): bool
    {
        return $this->nominal === $nominal->nominal;
    }
}
