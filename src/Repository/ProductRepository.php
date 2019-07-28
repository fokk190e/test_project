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
        $date = date('Y-m-d h:i:s', strtotime("-1 day"));

        $query = $this->
        getEntityManager()->
        createQuery('SELECT p 
                          FROM App\Entity\Product p 
                          WHERE p.createdAt BETWEEN :oneday AND :today
                          ');
        $query->setParameter('oneday', $date);
        $query->setParameter('today', date('Y-m-d h:i:s'));

        return $query->execute();
    }
}
