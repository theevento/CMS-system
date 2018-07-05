<?php

namespace App\Repository;

use App\Entity\ArticleTags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ArticleTags|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleTags|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleTags[]    findAll()
 * @method ArticleTags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleTagsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ArticleTags::class);
    }


    public function findTagsById($id)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.article = :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findTagsByIdArticle($id)
    {
        return $this->createQueryBuilder('a')
            ->select('a.tag, a.id')
            ->andWhere('a.article = :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findArticleByTagName($name)
    {
        return $this->createQueryBuilder('t')
            ->select('a.id, a.image, a.description, a.title')
            ->innerJoin('App:Article', 'a', 'WITH', 'a.id = t.article')
            ->andWhere('a.active = TRUE')
            ->andWhere('t.tag = :name')
            ->setParameter('name', $name)
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return ArticleTags[] Returns an array of ArticleTags objects
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
    public function findOneBySomeField($value): ?ArticleTags
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
