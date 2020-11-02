<?php

namespace App\Repository;

use App\Entity\CustomProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomProduct[]    findAll()
 * @method CustomProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomProduct::class);
    }

    // /**
    //  * @return CustomProduct[] Returns an array of CustomProduct objects
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
    public function findOneBySomeField($value): ?CustomProduct
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
