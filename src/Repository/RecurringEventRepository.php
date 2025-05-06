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

    //    /**
    //     * @return RecurringEvent[] Returns an array of RecurringEvent objects
    //     */
    public function findNextEvents(\DateTimeImmutable $today): array
    {
        
     //min((:totay - e.start_date))
        ($qb = $this->createQueryBuilder('r'))
            ->addSelect('a')
            ->innerJoin('r.recurringRule','a')
            ->andWhere($qb->expr()->eq( ':today','a.daysOfWeek'))
            ->andWhere($qb->expr()->eq( ':activ','a.isActive'))
            ->setParameter('activ', true)
            ->setParameter('today', $today->format('l'))
            ;
            // dd($qb ->getQuery()->getResult());
         return $qb ->getQuery()->getResult();
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
