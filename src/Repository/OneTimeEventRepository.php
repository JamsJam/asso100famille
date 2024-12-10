<?php

namespace App\Repository;

use App\Entity\OneTimeEvent;
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

    public function findThisWeekEvent($today): ?OneTimeEvent
    {
        $qb =  $this->createQueryBuilder('p');
        $qb ->andWhere( $qb->expr()->between(':today', 'p.startDate', 'p.$endDate'))
            ->setParameter('today', $today)
            
            

        ;

        return  $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return OneTimeEvent[] Returns an array of OneTimeEvent objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

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
