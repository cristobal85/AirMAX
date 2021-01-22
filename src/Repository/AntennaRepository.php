<?php

namespace App\Repository;

use App\Entity\Antenna;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Antenna|null find($id, $lockMode = null, $lockVersion = null)
 * @method Antenna|null findOneBy(array $criteria, array $orderBy = null)
 * @method Antenna[]    findAll()
 * @method Antenna[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AntennaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Antenna::class);
    }

    // /**
    //  * @return Antenna[] Returns an array of Antenna objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Antenna
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
