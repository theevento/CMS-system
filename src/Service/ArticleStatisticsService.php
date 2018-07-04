<?php

namespace App\Service;


use App\Entity\Article;
use App\Entity\ArticleStatistics;
use App\Repository\ArticleStatisticsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleStatisticsService
{
    private $userIp;
    private $articleStatistics;
    private $articleStatisticsRepository;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, ArticleStatisticsRepository $articleStatisticsRepository)
    {
        $this->userIp = $requestStack->getCurrentRequest()->getClientIp();
        $this->articleStatistics = new ArticleStatistics();
        $this->entityManager = $entityManager;
        $this->articleStatisticsRepository = $articleStatisticsRepository;
    }

    public function addViewToDatabases(Article $article):void
    {
        if(!$this->CheckUserIpInDatabases($article))
        {
            $this->articleStatistics->setIp($this->userIp)
                ->setArticle($article);
            $this->entityManager->persist($this->articleStatistics);
            $this->entityManager->flush();
        }
    }

    private function CheckUserIpInDatabases(Article $article):bool
    {
        $articleStatistics = $this->articleStatisticsRepository->findOneBy(['ip' => $this->userIp, 'article' => $article]);
        if(!$articleStatistics)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}