<?php

namespace App\Repository;

use App\Entity\RecurringEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecurringEvent>
 */
class RecurringEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecurringEvent::class);
    }

    //    /**
    //     * @return RecurringEvent[] Returns an array of RecurringEvent objects
    //     */
       public function findActivEvents(): array
       {
            return $this->createQueryBuilder('r')
                ->addSelect('a')
                ->innerJoin('r.recurringRule','a')
                ->andWhere('a.isActive = :val')
                ->setParameter('val', true)
                ->orderBy('r.startDate', 'ASC')
                ->getQuery()
                ->getResult()
            ;
       }

    //    public function findOneBySomeField($value): ?RecurringEvent
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
