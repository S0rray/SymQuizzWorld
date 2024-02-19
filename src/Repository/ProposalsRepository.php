<?php

namespace App\Repository;

use App\Entity\Proposals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Proposals>
 *
 * @method Proposals|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proposals|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proposals[]    findAll()
 * @method Proposals[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProposalsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proposals::class);
    }

//    /**
//     * @return Proposals[] Returns an array of Proposals objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Proposals
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
