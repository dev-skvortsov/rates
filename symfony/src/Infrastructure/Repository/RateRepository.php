<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Rate;
use App\Domain\Repository\RateRepositoryInterface;
use App\Domain\ValueObject\Code;
use App\Domain\ValueObject\DateImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rate>
 */
class RateRepository extends ServiceEntityRepository implements RateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    public function storeMultiple(array $rates): void
    {
        foreach ($rates as $rate) {
            $this->getEntityManager()->persist($rate);
        }

        $this->getEntityManager()->flush();
    }

    public function getByCodeAndDate(Code $code, DateImmutable $date): ?Rate
    {
        return $this->find(['code' => $code, 'date' => $date]);
    }

    public function getPrevTradingDate(Rate $rate, DateImmutable $date): ?DateImmutable
    {
        $qb = $this->createQueryBuilder('r');

        $prevTradingDate = $qb->select('r.tradingDate')
            ->where('r.code = :code')
            ->andWhere('r.date < :date')
            ->andWhere('r.tradingDate < :tradingDate')
            ->setMaxResults(1)
            ->setParameter('code', $rate->code)
            ->setParameter('date', $rate->date)
            ->setParameter('tradingDate', $rate->tradingDate)
            ->orderBy('r.tradingDate', Criteria::DESC)
            ->addOrderBy('r.date', Criteria::DESC)
            ->getQuery()
            ->getSingleScalarResult();

        return new DateImmutable($prevTradingDate);
    }
}
