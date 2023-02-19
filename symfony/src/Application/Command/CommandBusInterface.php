<?php

namespace App\Application\Command;

interface CommandBusInterface
{
    /**
     * @template T
     * @param CommandInterface<T> $command
     * @return T
     */
    public function execute(CommandInterface $command): mixed;
}