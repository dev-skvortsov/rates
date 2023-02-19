<?php

declare(strict_types=1);

namespace App\Application\Command\Rate;

use App\Application\Command\CommandInterface;
use App\Application\Command\Rate\Dto\RatesDTO;

/**
 * @implements CommandInterface<void>
 */
final readonly class SaveRatesCommand implements CommandInterface
{
    public function __construct(public \DateTimeImmutable $date, public RatesDTO $ratesDTO)
    {
    }
}
