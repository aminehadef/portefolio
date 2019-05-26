<?php

namespace App\Repository;

use App\Entity\Passion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Passion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Passion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Passion[]    findAll()
 * @method Passion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PassionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Passion::class);
    }

    // /**
    //  * @return Passion[] Returns an array of Passion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Passion
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
