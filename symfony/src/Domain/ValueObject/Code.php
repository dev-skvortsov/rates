<?php

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
        if (mb_strlen($code) != self::ISO4217_CODE_LENGTH) {
            throw new \DomainException('Code length must be equal 3 symbols');
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
}