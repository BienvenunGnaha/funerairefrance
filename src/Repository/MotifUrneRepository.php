<?php

namespace App\Repository;

use App\Entity\MotifUrne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MotifUrne|null find($id, $lockMode = null, $lockVersion = null)
 * @method MotifUrne|null findOneBy(array $criteria, array $orderBy = null)
 * @method MotifUrne[]    findAll()
 * @method MotifUrne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotifUrneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MotifUrne::class);
    }

    // /**
    //  * @return MotifUrne[] Returns an array of MotifUrne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MotifUrne
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
