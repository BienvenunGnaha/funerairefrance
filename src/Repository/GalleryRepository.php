<?php

namespace App\Repository;

use App\Entity\Gallery;
use App\Entity\Product;
use App\Entity\Fixation;
use App\Entity\Stele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gallery[]    findAll()
 * @method Gallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gallery::class);
    }

     /**
      * @return Gallery[] Returns an array of Gallery objects
      */
    
    public function findByGranit(Product $product)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.product = :prod')
            ->andWhere('g.granit != :granit')
            ->andWhere('g.stele = :stele')
            ->andWhere('g.fixation = :fixation')
            ->setParameter('prod', $product->getId())
            ->setParameter('granit', null)
            ->setParameter('stele', null)
            ->setParameter('fixation', null)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByStele(Product $product)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.product = :prod')
            ->andWhere('g.granit != :granit')
            ->andWhere('g.stele != :stele')
            ->setParameter('prod', $product->getId())
            ->setParameter('granit', null)
            ->setParameter('stele', null)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByFixation(Product $product)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.product = :prod')
            ->andWhere('g.granit != :granit')
            ->andWhere('g.fixation != :fixation')
            ->setParameter('prod', $product->getId())
            ->setParameter('granit', null)
            ->setParameter('fixation', null)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Gallery
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
