<?php

namespace App\Repository;

use App\Entity\Sepulture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sepulture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sepulture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sepulture[]    findAll()
 * @method Sepulture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SepultureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sepulture::class);
    }

    // /**
    //  * @return Sepulture[] Returns an array of Sepulture objects
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
    public function findOneBySomeField($value): ?Sepulture
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
