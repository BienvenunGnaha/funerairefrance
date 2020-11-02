<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

     /**
      * @return Message[] Returns an array of Message objects
      */
    public function ListMessages(User $user, $offset=null)
    {
        if((int)$offset !== 0){
            return $this->createQueryBuilder('m')
            ->where('m.sender = :sender')
            ->orWhere('m.receiver = :receiver')
            ->setParameter('sender', $user->getId())
            ->setParameter('receiver', $user->getId())
            ->orderBy('m.createdAt', 'DESC')
            ->setFirstResult( (int)$offset )
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
        }
        return $this->createQueryBuilder('m')
            ->where('m.sender = :sender')
            ->orWhere('m.receiver = :receiver')
            ->setParameter('sender', $user->getId())
            ->setParameter('receiver', $user->getId())
            ->orderBy('m.createdAt', 'DESC')
           // ->setFirstResult( 0 )
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
