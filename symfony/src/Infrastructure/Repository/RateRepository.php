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
use function _PHPStan_d3e3292d7\RingCentral\Psr7\_parse_request_uri;

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

    public function getPrevTradingDate(DateImmutable $date): DateImmutable
    {
        $query = $this->getEntityManager()->getConnection()->prepare('
            SELECT trading_date
            FROM rates r
            WHERE r.trading_date < :date
                AND r.date < :date
            ORDER BY r.trading_date DESC    
            LIMIT 1
        ');

        $prevTradingDate = $query->executeQuery(['date' => $date->format(DateImmutable::FORMAT)])
            ->fetchOne();

        if (!$prevTradingDate) {
            throw new \DomainException('No previous trading date');
        }

        return new DateImmutable($prevTradingDate);
    }

    public function get(Code $baseCode, Code $code, DateImmutable $date): Rate
    {
        $rate = $this->find([
            'baseCode' => $baseCode,
            'code' => $code,
            'date' => $date,
        ]);

        if (null === $rate) {
            throw new \DomainException('Rate not found');
        }

        return $rate;
    }
}
