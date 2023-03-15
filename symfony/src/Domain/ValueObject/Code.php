<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final readonly class Code
{
    public const RUR_CODE = 'RUR';

    public const ISO4217_CODE_LENGTH = 3;

    private function __construct(public string $code)
    {
    }

    public static function create(string $code): Code
    {
        if (self::ISO4217_CODE_LENGTH != mb_strlen($code)) {
            throw new \DomainException(sprintf('Code length must be equal %d symbols', self::ISO4217_CODE_LENGTH));
        }

        return new self(mb_strtoupper($code));
    }

    public function isEqual(Code $code): bool
    {
        return $this->code === $code->code;
    }

    public function __toString(): string
    {
        return $this->code;
    }

    public static function createRUR(): Code
    {
        return self::create(self::RUR_CODE);
    }
}
