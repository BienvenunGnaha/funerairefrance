<?php

namespace App\Repository;

use App\Entity\ProdFixation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProdFixation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProdFixation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProdFixation[]    findAll()
 * @method ProdFixation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProdFixationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdFixation::class);
    }

    // /**
    //  * @return ProdFixation[] Returns an array of ProdFixation objects
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
    public function findOneBySomeField($value): ?ProdFixation
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
