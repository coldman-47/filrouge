<?php

namespace App\Repository;

use App\Entity\DetailBriefCompetence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DetailBriefCompetence|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailBriefCompetence|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailBriefCompetence[]    findAll()
 * @method DetailBriefCompetence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailBriefCompetenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailBriefCompetence::class);
    }

    // /**
    //  * @return DetailBriefCompetence[] Returns an array of DetailBriefCompetence objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DetailBriefCompetence
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
