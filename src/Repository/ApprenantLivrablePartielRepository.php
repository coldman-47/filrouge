<?php

namespace App\Repository;

use App\Entity\ApprenantLivrablePartiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApprenantLivrablePartiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApprenantLivrablePartiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApprenantLivrablePartiel[]    findAll()
 * @method ApprenantLivrablePartiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprenantLivrablePartielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApprenantLivrablePartiel::class);
    }

    // /**
    //  * @return ApprenantLivrablePartiel[] Returns an array of ApprenantLivrablePartiel objects
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
    public function findOneBySomeField($value): ?ApprenantLivrablePartiel
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
