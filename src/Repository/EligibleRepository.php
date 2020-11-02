<?php

namespace App\Repository;

use App\Entity\Eligible;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Eligible|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eligible|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eligible[]    findAll()
 * @method Eligible[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EligibleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eligible::class);
    }

    // /**
    //  * @return Eligible[] Returns an array of Eligible objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Eligible
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
