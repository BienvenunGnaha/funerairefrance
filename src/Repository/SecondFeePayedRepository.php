<?php

namespace App\Repository;

use App\Entity\SecondFeePayed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SecondFeePayed|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecondFeePayed|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecondFeePayed[]    findAll()
 * @method SecondFeePayed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecondFeePayedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecondFeePayed::class);
    }

    // /**
    //  * @return SecondFeePayed[] Returns an array of SecondFeePayed objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SecondFeePayed
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
