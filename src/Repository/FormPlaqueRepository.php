<?php

namespace App\Repository;

use App\Entity\FormPlaque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FormPlaque|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormPlaque|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormPlaque[]    findAll()
 * @method FormPlaque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormPlaqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormPlaque::class);
    }

    // /**
    //  * @return FormPlaque[] Returns an array of FormPlaque objects
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
    public function findOneBySomeField($value): ?FormPlaque
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
