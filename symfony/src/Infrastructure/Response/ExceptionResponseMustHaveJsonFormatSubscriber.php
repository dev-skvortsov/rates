<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Response with JSON format on any Exception.
 */
class ExceptionResponseMustHaveJsonFormatSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [ExceptionEvent::class => 'onExceptionEvent'];
    }

    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        $request->setRequestFormat('application/problem+json');
    }
}
