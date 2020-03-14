<?php

namespace App\Repository;

use App\Entity\SpecialCapacity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SpecialCapacity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecialCapacity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecialCapacity[]    findAll()
 * @method SpecialCapacity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialCapacityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecialCapacity::class);
    }

    // /**
    //  * @return SpecialCapacity[] Returns an array of SpecialCapacity objects
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
    public function findOneBySomeField($value): ?SpecialCapacity
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
