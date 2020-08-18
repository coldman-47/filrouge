<?php

namespace App\Repository;

use App\Entity\EtatBrief;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtatBrief|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtatBrief|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtatBrief[]    findAll()
 * @method EtatBrief[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatBriefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatBrief::class);
    }

    // /**
    //  * @return EtatBrief[] Returns an array of EtatBrief objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EtatBrief
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
