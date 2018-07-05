<?php

namespace App\Controller;

use App\Repository\ArticleTagsRepository;
use App\Service\CustomSerializer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TagsController extends Controller
{
    /**
     * @Route("/tags/{name}", name="tags")
     */
    public function index($name, ArticleTagsRepository $articleTagsRepository, CustomSerializer $serializer)
    {
        $article = $articleTagsRepository->findArticleByTagName($name);
        if(!$article)
        {
            throw new NotFoundHttpException('Nie znaleziono artykułów o podanym tagu.');
        }

        return $this->render('tags/index.html.twig', [
            'article' => $serializer->serialize($article, 'json')
        ]);
    }
}
