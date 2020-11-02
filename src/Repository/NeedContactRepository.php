<?php

namespace App\Repository;

use App\Entity\NeedContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NeedContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method NeedContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method NeedContact[]    findAll()
 * @method NeedContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NeedContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NeedContact::class);
    }

    // /**
    //  * @return NeedContact[] Returns an array of NeedContact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NeedContact
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
