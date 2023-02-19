<?php

namespace App\Application\Query\Rate;

use App\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<RateDTO|null>
 */
final readonly class GetRateQuery implements QueryInterface
{
    public function __construct(public string $code, public \DateTime $date, public string $baseCode)
    {
    }
}