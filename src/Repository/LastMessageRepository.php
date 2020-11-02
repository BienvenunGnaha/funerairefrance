<?php

namespace App\Repository;

use App\Entity\LastMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @method LastMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method LastMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method LastMessage[]    findAll()
 * @method LastMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LastMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LastMessage::class);
    }

     /**
      * @return LastMessage[] Returns an array of LastMessage objects
      */
    
    public function findLast(User $user)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user != :user')
            ->setParameter('user', $user->getId())
            ->orderBy('l.lastAt', 'DESC')
            ->setMaxResults(15)
            ->getQuery()
            ->getResult()
        ;
    }
    

    
    /*public function findOneByUser(User $user): ?LastMessage
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }*/
    
}
