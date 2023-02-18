<?php

namespace App\Infrastructure\Request\Resolve;

use Symfony\Component\HttpFoundation\Request;

interface RequestInterface
{
    public function __construct(Request $request);
}