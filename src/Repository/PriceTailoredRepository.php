<?php

namespace App\Repository;

use App\Entity\PriceTailored;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PriceTailored|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceTailored|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceTailored[]    findAll()
 * @method PriceTailored[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceTailoredRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceTailored::class);
    }

    // /**
    //  * @return PriceTailored[] Returns an array of PriceTailored objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PriceTailored
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
