<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
        public function findProducts(string $ids): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.id IN (:ids)')
                ->setParameter('ids', \explode(',', $ids))
                ->orderBy('p.id', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }

}
