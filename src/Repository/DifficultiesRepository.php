<?php

namespace App\Repository;

use App\Entity\Difficulties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Difficulties>
 *
 * @method Difficulties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Difficulties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Difficulties[]    findAll()
 * @method Difficulties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DifficultiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Difficulties::class);
    }

//    /**
//     * @return Difficulties[] Returns an array of Difficulties objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Difficulties
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
