<?php

namespace App\Repository;

use App\Entity\Childcategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Childcategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Childcategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Childcategory[]    findAll()
 * @method Childcategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChildcategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Childcategory::class);
    }

    // /**
    //  * @return Childcategory[] Returns an array of Childcategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Childcategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
