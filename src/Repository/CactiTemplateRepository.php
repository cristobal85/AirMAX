<?php

namespace App\Repository;

use App\Entity\CactiTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CactiTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CactiTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CactiTemplate[]    findAll()
 * @method CactiTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CactiTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CactiTemplate::class);
    }

    // /**
    //  * @return CactiTemplate[] Returns an array of CactiTemplate objects
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
    public function findOneBySomeField($value): ?CactiTemplate
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
