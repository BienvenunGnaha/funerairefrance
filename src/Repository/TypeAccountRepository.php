<?php

namespace App\Repository;

use App\Entity\TypeAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeAccount[]    findAll()
 * @method TypeAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeAccount::class);
    }

    // /**
    //  * @return TypeAccount[] Returns an array of TypeAccount objects
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
    public function findOneBySomeField($value): ?TypeAccount
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
