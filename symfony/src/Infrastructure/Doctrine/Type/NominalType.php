<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\ValueObject\Nominal;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class NominalType extends Type
{
    public const TYPE_NAME = 'nominal';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'Rate nominal';
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Nominal
    {
        try {
            return Nominal::create($value);
        } catch (\DomainException $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }
    }

    /**
     * @param Nominal $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value->nominal;
    }
}
