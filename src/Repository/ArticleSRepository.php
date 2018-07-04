<?php

namespace App\Repository;

use App\Entity\ArticleS;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ArticleS|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleS|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleS[]    findAll()
 * @method ArticleS[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleSRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ArticleS::class);
    }

//    /**
//     * @return ArticleS[] Returns an array of ArticleS objects
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
    public function findOneBySomeField($value): ?ArticleS
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
