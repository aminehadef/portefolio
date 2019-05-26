<?php

namespace App\Repository;

use App\Entity\CreatedBy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CreatedBy|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreatedBy|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreatedBy[]    findAll()
 * @method CreatedBy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreatedByRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CreatedBy::class);
    }

    // /**
    //  * @return CreatedBy[] Returns an array of CreatedBy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CreatedBy
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
