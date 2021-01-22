<?php

namespace App\Repository;

use App\Entity\CactiConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CactiConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method CactiConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method CactiConfig[]    findAll()
 * @method CactiConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CactiConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CactiConfig::class);
    }

    // /**
    //  * @return CactiConfig[] Returns an array of CactiConfig objects
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
    public function findOneBySomeField($value): ?CactiConfig
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
