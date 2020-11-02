<?php

namespace App\Repository;

use App\Entity\Fixation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fixation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fixation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fixation[]    findAll()
 * @method Fixation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FixationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fixation::class);
    }

    // /**
    //  * @return Fixation[] Returns an array of Fixation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fixation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
