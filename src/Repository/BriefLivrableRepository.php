<?php

namespace App\Repository;

use App\Entity\BriefLivrable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BriefLivrable|null find($id, $lockMode = null, $lockVersion = null)
 * @method BriefLivrable|null findOneBy(array $criteria, array $orderBy = null)
 * @method BriefLivrable[]    findAll()
 * @method BriefLivrable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefLivrableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BriefLivrable::class);
    }

    // /**
    //  * @return BriefLivrable[] Returns an array of BriefLivrable objects
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
    public function findOneBySomeField($value): ?BriefLivrable
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
