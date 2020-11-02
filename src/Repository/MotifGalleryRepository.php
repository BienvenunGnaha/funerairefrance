<?php

namespace App\Repository;

use App\Entity\MotifGallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MotifGallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method MotifGallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method MotifGallery[]    findAll()
 * @method MotifGallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotifGalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MotifGallery::class);
    }

     /**
      * @return MotifGallery[] Returns an array of MotifGallery objects
      */
    
    public function search($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.name LIKE :name')
            ->setParameter('name', '%'.$value.'%')
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?MotifGallery
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
