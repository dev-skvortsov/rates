<?php

namespace App\Infrastructure\Controller;

use App\Application\Query\QueryBusInterface;
use App\Application\Query\Rate\GetRateQuery;
use App\Infrastructure\Request\RateRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class RatesController extends AbstractController
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    #[Route('/v1/rates', name: 'rates')]
    public function __invoke(RateRequest $request): JsonResponse
    {
        // todo http cache
        $rateDTO = $this->queryBus->execute(new GetRateQuery(
            $request->getCode(),
            $request->getDate(),
            $request->getBaseCode()
        ));

        return $this->json($rateDTO);
    }
}