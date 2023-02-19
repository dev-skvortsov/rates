<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\SaveRatesCommand;
use App\Domain\Entity\Rate;
use App\Infrastructure\Service\CbrRatesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetCbrRatesHistoryCommand extends Command
{
    public function __construct(
        private readonly CbrRatesService $cbrRatesService,
        private readonly CommandBusInterface $commandBus,
    ) {
        parent::__construct('cbr:rates:history');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = new \DateTime();

        while ($date->sub(new \DateInterval('P1D')) >= Rate::getStartTradingDate()) {
            try {
                $ratesDTO = $this->cbrRatesService->getRates($date);

                $this->commandBus->execute(new SaveRatesCommand(\DateTimeImmutable::createFromMutable($date), $ratesDTO));
            } catch (\Throwable $e) {
                continue;
            }
        }

        return Command::SUCCESS;
    }
}
