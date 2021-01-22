<?php

namespace App\Repository;

use App\Entity\AntennaSignal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AntennaSignal|null find($id, $lockMode = null, $lockVersion = null)
 * @method AntennaSignal|null findOneBy(array $criteria, array $orderBy = null)
 * @method AntennaSignal[]    findAll()
 * @method AntennaSignal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AntennaSignalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AntennaSignal::class);
    }

    // /**
    //  * @return AntennaSignal[] Returns an array of AntennaSignal objects
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
    public function findOneBySomeField($value): ?AntennaSignal
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
