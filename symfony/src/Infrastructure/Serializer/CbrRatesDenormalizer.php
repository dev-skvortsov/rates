<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use App\Application\Command\Rate\Dto\RateDTO;
use App\Application\Command\Rate\Dto\RatesDTO;
use App\Domain\ValueObject\Code;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CbrRatesDenormalizer implements DenormalizerInterface
{
    public const DATE = '@Date';
    public const DATE_FORMAT = 'd.m.Y';
    public const VALUTE = 'Valute';
    public const CODE = 'CharCode';
    public const NOMINAL = 'Nominal';
    public const VALUE = 'Value';

    /**
     * @param array{string, string} $data
     * @param array<string, mixed> $context
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): RatesDTO
    {
        $rates = $this->denormalizeRates($data[self::VALUTE]);

        return new RatesDTO(
            \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $data[self::DATE]),
            $rates
        );
    }

    /**
     * @return RateDTO[]
     */
    private function denormalizeRates(array $rates): array
    {
        return array_map(function ($rate) {
            return new RateDTO(
                Code::RUR_CODE,
                $rate[self::CODE],
                intval($rate[self::NOMINAL]),
                floatval(str_replace(',', '.', $rate[self::VALUE])),
            );
        }, $rates);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return RatesDTO::class === $type;
    }
}
