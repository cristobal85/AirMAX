<?php

namespace App\Repository;

use App\Entity\SnmpCredential;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SnmpCredential|null find($id, $lockMode = null, $lockVersion = null)
 * @method SnmpCredential|null findOneBy(array $criteria, array $orderBy = null)
 * @method SnmpCredential[]    findAll()
 * @method SnmpCredential[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SnmpCredentialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SnmpCredential::class);
    }

    // /**
    //  * @return SnmpCredential[] Returns an array of SnmpCredential objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SnmpCredential
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
