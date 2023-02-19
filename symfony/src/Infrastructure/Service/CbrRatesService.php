<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\Command\Rate\Dto\RateDTO;
use App\Application\Command\Rate\Dto\RatesDTO;
use App\Domain\ValueObject\Code;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class CbrRatesService
{
    public const DATE_FORMAT = 'd/m/Y';

    public function __construct(private SerializerInterface $serializer, private HttpClientInterface $cbrClient)
    {
    }

    public function getRates(\DateTime $date): RatesDTO
    {
        $xml = $this->getRatesXml($date);

        /** @var RatesDTO $ratesDTO */
        $ratesDTO = $this->serializer->deserialize($xml, RatesDTO::class, 'xml');

        $rurRate = new RateDTO(
            Code::RUR_CODE,
            1,
            1.0,
            Code::RUR_CODE
        );

        return new RatesDTO($ratesDTO->tradingDate, array_merge($ratesDTO->rates, [$rurRate]));
    }

    private function getRatesXml(\DateTime $date): string
    {
        return $this->cbrClient->request(Request::METHOD_GET, '/scripts/XML_daily.asp', [
            'query' => [
                'date_req' => $date->format(self::DATE_FORMAT),
            ],
        ])->getContent();
    }
}
