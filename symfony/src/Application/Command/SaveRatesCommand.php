<?php

namespace App\Application\Command;

use App\Application\Command\Dto\RatesDTO;

/**
 * @implements CommandInterface<void>
 */
final readonly class SaveRatesCommand implements CommandInterface
{
    public function __construct(public \DateTimeImmutable $date, public RatesDTO $ratesDTO)
    {
    }
}