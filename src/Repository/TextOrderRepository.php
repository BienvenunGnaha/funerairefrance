<?php

namespace App\Repository;

use App\Entity\TextOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TextOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextOrder[]    findAll()
 * @method TextOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextOrder::class);
    }

    // /**
    //  * @return TextOrder[] Returns an array of TextOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TextOrder
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
