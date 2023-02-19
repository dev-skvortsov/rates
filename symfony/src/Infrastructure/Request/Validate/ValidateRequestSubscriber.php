<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Validate;

use Phpro\ApiProblem\Exception\ApiProblemException;
use Phpro\ApiProblem\Http\ValidationApiProblem;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ValidateRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerArgumentsEvent::class => 'validateRequest',
        ];
    }

    public function validateRequest(ControllerArgumentsEvent $event): void
    {
        // there can be more than one argument
        $customRequestArguments = array_filter($event->getArguments(), fn ($argument) => $argument instanceof ValidatedRequestInterface);
        if (empty($customRequestArguments)) {
            return;
        }

        /** @var ConstraintViolationListInterface[] $violations */
        $violations = array_map(fn ($argument) => $this->validator->validate($argument), $customRequestArguments);
        $notEmptyViolations = array_filter($violations, fn (ConstraintViolationListInterface $violation) => $violation->count() > 0);

        if (empty($notEmptyViolations)) {
            return;
        }

        $violations = array_shift($notEmptyViolations);
        foreach ($notEmptyViolations as $violation) {
            $violations->addAll($violation);
        }

        throw new ApiProblemException(new ValidationApiProblem($violations));
    }
}
