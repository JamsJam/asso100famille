<?php

namespace App\Repository;

use App\Entity\Adherent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adherent>
 */
class AdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherent::class);
    }

    //    /**
    //     * @return Adherent[] Returns an array of Adherent objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }


    public function getMonthlyCount($dateLimite): ?int
    {
        $qb =  $this->createQueryBuilder('a');
        $result= $qb
            ->select('COUNT(a.id)') // Sélectionne le nombre d'adhérents
            ->join('a.abonement', 'abo')
            ->Where([ 'abo.status = :status' ])
            ->andWhere($qb->expr()->lte('abo.createdAt', ':date'))
            ->setParameter('status', 'active')
            ->setParameter('date', $dateLimite)
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
        
    }
}
