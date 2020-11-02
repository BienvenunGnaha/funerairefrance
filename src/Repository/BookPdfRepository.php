<?php

namespace App\Repository;

use App\Entity\BookPdf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BookPdf|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookPdf|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookPdf[]    findAll()
 * @method BookPdf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookPdfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookPdf::class);
    }

    // /**
    //  * @return BookPdf[] Returns an array of BookPdf objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BookPdf
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
