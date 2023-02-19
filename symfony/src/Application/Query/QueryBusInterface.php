<?php

namespace App\Application\Query;

interface QueryBusInterface
{
    /**
     * @template T
     * @param QueryInterface<T> $query
     * @return T
     */
    public function execute(QueryInterface $query): mixed;
}