<?php

namespace App\Repository;

use App\Entity\Themes;
use App\Entity\Questions;
use App\Entity\Difficulties;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Questions>
 *
 * @method Questions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questions[]    findAll()
 * @method Questions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questions::class);
    }

    public function findHighestQuestionNumberForThemeAndDifficulty(Themes $theme, Difficulties $difficulty): ?int
    {
        return $this->createQueryBuilder('q')
            ->select('MAX(q.number) AS highest_number')
            ->where('q.theme = :theme')
            ->andWhere('q.difficulty = :difficulty')
            ->setParameter('theme', $theme)
            ->setParameter('difficulty', $difficulty)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findHighestQuestionNumberForTheme(Themes $theme): ?int
    {
        return $this->createQueryBuilder('q')
            ->select('MAX(q.number) AS highest_number')
            ->where('q.theme = :theme')
            ->setParameter('theme', $theme)
            ->getQuery()
            ->getSingleScalarResult();
    }


//    /**
//     * @return Questions[] Returns an array of Questions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Questions
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
