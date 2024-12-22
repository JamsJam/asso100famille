<?php

namespace App\Repository;

use App\Entity\OneTimeEvent;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OneTimeEvent>
 */
class OneTimeEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OneTimeEvent::class);
    }

    public function findThisWeekEvent($today): ? array
    {
        ($qb = $this->createQueryBuilder('e'))
                ->andWhere($qb->expr()->between('e.startDate', ':today', ':NextDay'))
                ->orderBy('e.id', 'ASC')
                ->setParameter('today', $today)
                ->setParameter('NextDay', $today->modify("+1 week"));

            return $qb ->getQuery()->getResult();
    }

    //    /**
    //     * @return OneTimeEvent[] Returns an array of OneTimeEvent objects
    //     */
       public function findThisMonthEvent(\DateTimeImmutable $today): array
       {

            ($qb = $this->createQueryBuilder('e'))
                ->andWhere($qb->expr()->between('e.startDate', ':today', ':NextDay'))
                ->orderBy('e.id', 'ASC')
                ->setParameter('today', $today)
                ->setParameter('NextDay', $today->modify("+1 month"));

            return $qb ->getQuery()->getResult();
       }

    //    public function findOneBySomeField($value): ?OneTimeEvent
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
