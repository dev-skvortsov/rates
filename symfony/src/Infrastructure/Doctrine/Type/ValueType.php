<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\ValueObject\Value;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class ValueType extends Type
{
    public const TYPE_NAME = 'value';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'Rate value';
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        try {
            return Value::create(floatval($value));
        } catch (\DomainException $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }
    }

    /**
     * @param Value $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value->value;
    }
}
