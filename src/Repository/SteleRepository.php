<?php

namespace App\Repository;

use App\Entity\Stele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stele|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stele|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stele[]    findAll()
 * @method Stele[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SteleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stele::class);
    }

    // /**
    //  * @return Stele[] Returns an array of Stele objects
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
    public function findOneBySomeField($value): ?Stele
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
