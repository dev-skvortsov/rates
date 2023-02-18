<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\Resolve;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class RequestResolver implements ValueResolverInterface
{
    /**
     * @return RequestInterface[]
     */
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        $argumentType = $argument->getType();
        if (
            !$argumentType
            || !is_subclass_of($argumentType, RequestInterface::class)
        ) {
            return [];
        }

        // create and return the value object
        return [new $argumentType($request)];
    }
}
