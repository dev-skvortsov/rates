<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\ValueObject\Code;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class CodeType extends Type
{
    public const TYPE_NAME = 'code';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'Currency code';
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Code
    {
        try {
            return Code::create($value);
        } catch (\DomainException $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }
    }

    /**
     * @param Code $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value->code;
    }
}
