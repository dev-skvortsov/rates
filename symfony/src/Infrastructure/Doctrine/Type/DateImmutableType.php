<?php

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\ValueObject\DateImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class DateImmutableType extends \Doctrine\DBAL\Types\DateImmutableType
{
    public const TYPE = 'pk_date_immutable';

    public function getName(): string
    {
        return self::TYPE;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateImmutable) {
            return $value;
        }

        $dateTime = DateImmutable::createFromFormat('!' . $platform->getDateFormatString(), $value);

        if ($dateTime === false) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateFormatString(),
            );
        }

        return $dateTime;
    }
}