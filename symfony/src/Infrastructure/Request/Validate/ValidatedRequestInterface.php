<?php

namespace App\Infrastructure\Request\Validate;

use Symfony\Component\Validator\Mapping\ClassMetadata;

interface ValidatedRequestInterface
{
    /** @see https://symfony.com/doc/current/validation.html */
    public static function loadValidatorMetadata(ClassMetadata $metadata): void;
}