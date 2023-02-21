<?php

namespace App\Infrastructure\Bus\Middleware;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

readonly class ClearEntityManagerMiddleware implements MiddlewareInterface
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);
        $this->clearManagers();

        return $envelope;
    }

    private function clearManagers(): void
    {
        foreach ($this->managerRegistry->getManagers() as $manager) {
            $manager->clear();
        }
    }
}