<?php

declare(strict_types=1);

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
        if (null === $value || $value instanceof DateImmutable) {
            return $value;
        }

        $dateTime = DateImmutable::createFromFormat('!'.$platform->getDateFormatString(), $value);

        if (false === $dateTime) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateFormatString());
        }

        return $dateTime;
    }
}
