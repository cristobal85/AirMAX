<?php

namespace App\Repository;

use App\Entity\DhcpConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DhcpConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method DhcpConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method DhcpConfig[]    findAll()
 * @method DhcpConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DhcpConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DhcpConfig::class);
    }

    /**
     * @return DhcpConfig 
     */
    public function getConfig(): DhcpConfig
    {
        return $this->createQueryBuilder('d')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?DhcpConfig
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
