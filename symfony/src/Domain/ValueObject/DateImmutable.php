<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class DateImmutable extends \DateTimeImmutable
{
    public const FORMAT = 'Y-m-d';

    public function __toString(): string
    {
        return $this->format(self::FORMAT);
    }

    public function isEqual(DateImmutable $date): bool
    {
        return $this->format(self::FORMAT) === $date->format(self::FORMAT);
    }

    public static function createFromDateTimeImmutable(\DateTimeImmutable $dateTimeImmutable): DateImmutable
    {
        return static::createFromMutable(
            \DateTime::createFromImmutable($dateTimeImmutable)
        );
    }

    public static function createFromMutable(\DateTime $object): DateImmutable
    {
        return parent::createFromMutable($object);
    }
}
