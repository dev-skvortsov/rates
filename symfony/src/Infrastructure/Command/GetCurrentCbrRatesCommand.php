<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\SaveRatesCommand;
use App\Infrastructure\Service\CbrRatesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetCurrentCbrRatesCommand extends Command
{
    public function __construct(private readonly CbrRatesService $cbrRatesService, private readonly CommandBusInterface $commandBus)
    {
        parent::__construct('cbr:rates:current');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $today = new \DateTime();

        try {
            $ratesDTO = $this->cbrRatesService->getRates($today);

            $this->commandBus->execute(new SaveRatesCommand(\DateTimeImmutable::createFromMutable($today), $ratesDTO));
        } catch (\Throwable $e) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
