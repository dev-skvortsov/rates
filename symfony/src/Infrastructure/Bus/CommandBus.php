<?php

namespace App\Infrastructure\Bus;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\CommandInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function execute(CommandInterface $command): mixed
    {
        return $this->handle($command);
    }
}