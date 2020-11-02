<?php

namespace App\Repository;

use App\Entity\RequiredUpdatePm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequiredUpdatePm|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequiredUpdatePm|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequiredUpdatePm[]    findAll()
 * @method RequiredUpdatePm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequiredUpdatePmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequiredUpdatePm::class);
    }

    // /**
    //  * @return RequiredUpdatePm[] Returns an array of RequiredUpdatePm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RequiredUpdatePm
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
