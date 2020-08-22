<?php

namespace App\Repository;

use App\Entity\CompetenceValide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompetenceValide|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetenceValide|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetenceValide[]    findAll()
 * @method CompetenceValide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenceValideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetenceValide::class);
    }

    // /**
    //  * @return CompetenceValide[] Returns an array of CompetenceValide objects
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
    public function findOneBySomeField($value): ?CompetenceValide
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
