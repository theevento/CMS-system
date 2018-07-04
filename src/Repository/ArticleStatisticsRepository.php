<?php

namespace App\Repository;

use App\Entity\ArticleStatistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ArticleStatistics|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleStatistics|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleStatistics[]    findAll()
 * @method ArticleStatistics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleStatisticsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ArticleStatistics::class);
    }

//    /**
//     * @return ArticleStatisticsService[] Returns an array of ArticleStatisticsService objects
//     */
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
    public function findOneBySomeField($value): ?ArticleStatisticsService
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
