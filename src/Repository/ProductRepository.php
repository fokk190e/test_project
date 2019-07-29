<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getLastDayProduct()
    {
        return $this->createQueryBuilder('p')
            ->where('p.createdAt BETWEEN :yesterday AND :today')
            ->setParameter('yesterday', new \DateTime('-1 day'))
            ->setParameter('today', new \DateTime())
            ->getQuery()
            ->getResult();
    }
}
