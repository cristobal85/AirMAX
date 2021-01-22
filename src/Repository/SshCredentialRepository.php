<?php

namespace App\Repository;

use App\Entity\SshCredential;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SshCredential|null find($id, $lockMode = null, $lockVersion = null)
 * @method SshCredential|null findOneBy(array $criteria, array $orderBy = null)
 * @method SshCredential[]    findAll()
 * @method SshCredential[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SshCredentialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SshCredential::class);
    }

    // /**
    //  * @return SshCredential[] Returns an array of SshCredential objects
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
    public function findOneBySomeField($value): ?SshCredential
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
