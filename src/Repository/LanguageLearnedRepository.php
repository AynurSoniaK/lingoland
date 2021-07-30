<?php

namespace App\Repository;

use App\Entity\LanguageLearned;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LanguageLearned|null find($id, $lockMode = null, $lockVersion = null)
 * @method LanguageLearned|null findOneBy(array $criteria, array $orderBy = null)
 * @method LanguageLearned[]    findAll()
 * @method LanguageLearned[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LanguageLearnedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LanguageLearned::class);
    }

    // /**
    //  * @return LanguageLearned[] Returns an array of LanguageLearned objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LanguageLearned
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
