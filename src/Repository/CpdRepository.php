<?php

namespace App\Repository;

use App\Entity\Cpd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cpd|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cpd|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cpd[]    findAll()
 * @method Cpd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CpdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cpd::class);
    }

    // /**
    //  * @return Cpd[] Returns an array of Cpd objects
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
    public function findOneBySomeField($value): ?Cpd
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
