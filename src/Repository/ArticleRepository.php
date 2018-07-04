<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findSizeOfAllActiveArticle()
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id) as size')
            ->where('a.active = TRUE')
            ->getQuery()
            ->getResult();
    }

    public function findPopularArticles()
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, a.title, a.date, a.image')
            ->innerJoin('App:ArticleStatistics', 's', 'WITH', 's.article = a.id')
            ->where('a.active = TRUE')
            ->groupBy('a.id, a.title, a.date, a.image')
            ->setMaxResults(5)
            ->orderBy('COUNT(s.id)', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findArticlesByUser($id)
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, a.title, a.active, a.date, a.article, a.description')
            ->where('a.users = :id')
            ->orderBy('a.date', 'DESC')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findArticlesWithTagsByUser($id)
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, a.title, a.active, a.date, a.article, b.tag')
            ->innerJoin('App:ArticleTags', 'b', 'WITH', 'b.article  = a.id')
            ->where('a.user = :id')
            ->orderBy('a.date', 'DESC')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }


    public function findFullArticle($id)
    {
        return $this->createQueryBuilder('a')
            ->select('a.title, a.date, a.article, a.image, a.date')
            ->where('a.id = :id')
            ->andWhere('a.active = TRUE')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findArticleAuthorById($id)
    {
        return $this->createQueryBuilder('a')
            ->select('u.name')
            ->innerJoin('App:Users', 'u', 'WITH', 'a.users  = u.id')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllActiveArticleOnly($page)
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, a.title, a.active, a.date, a.article, a.image, a.description, COUNT(c.id) as comment_length')
            ->leftJoin('App:ArticleComments', 'c', 'WITH', 'c.article  = a.id')
            ->where('a.active = true')
            ->groupBy('a.id, a.title, a.active, a.date, a.article, a.image, a.description')
            ->orderBy('a.date', 'DESC')
            ->setFirstResult($page*6)
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Article[] Returns an array of Article objects
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
    public function findOneBySomeField($value): ?Article
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
