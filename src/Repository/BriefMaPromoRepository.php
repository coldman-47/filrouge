<?php

namespace App\Repository;

use App\Entity\BriefMaPromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BriefMaPromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method BriefMaPromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method BriefMaPromo[]    findAll()
 * @method BriefMaPromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefMaPromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BriefMaPromo::class);
    }

    // /**
    //  * @return BriefMaPromo[] Returns an array of BriefMaPromo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BriefMaPromo
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
