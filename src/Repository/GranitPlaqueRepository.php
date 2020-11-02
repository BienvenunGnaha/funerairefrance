<?php

namespace App\Repository;

use App\Entity\GranitPlaque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GranitPlaque|null find($id, $lockMode = null, $lockVersion = null)
 * @method GranitPlaque|null findOneBy(array $criteria, array $orderBy = null)
 * @method GranitPlaque[]    findAll()
 * @method GranitPlaque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GranitPlaqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GranitPlaque::class);
    }

    // /**
    //  * @return GranitPlaque[] Returns an array of GranitPlaque objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GranitPlaque
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
