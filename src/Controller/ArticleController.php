<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ArticleTagsRepository;
use App\Service\ArticleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends Controller
{
    /**
     * @Route("/admin/article/add/", name="article")
     */
    public function index(Request $request, ArticleService $articleService)
    {
        if($request->getMethod() === 'POST')
        {
          $result = $articleService->prepareArticle($request->get('title'), $request->get('active'), $request->get('article'), $request->files->get('file'), $request->get('description'))
                ->prepareTags($request->get('tags'))
                ->AddArticle();

            return new JsonResponse($result);
        }
        return $this->render('article/index.html.twig');
    }

    /**
     * @Route("/admin/article/edit/", name="edit_list_article")
     */

    public function editList(ArticleService $articleService, Request $request)
    {
        if($request->getMethod() === 'POST')
        {
            $result = $articleService->prepareTags($request->get('tags'))
                ->UpdateArticle($request->get('id'), $request->get('title'), $request->get('active'), $request->get('article'), $request->files->get('file'), $request->get('description'));
            return new JsonResponse($result);
        }


         return $this->render('article/edit_list.html.twig');
    }


    /**
     * @Route("/admin/api/article/", name="edit_list_api_article")
     */

    public function editListApiArticle(ArticleRepository $articleRepository, SerializerInterface $serializer)
    {
        return new JsonResponse($serializer->serialize($articleRepository->findArticlesByUser($this->getUser()), 'json'));
    }


    /**
     * @Route("/admin/api/tags/{id}", name="edit_list_api_tag")
     */

    public function editListApiTags($id, ArticleTagsRepository $tagsRepository)
    {
        $tags = $tagsRepository->findTagsById($id);
        $tags_string = '';
        foreach ($tags as $tag)
        {
            if ($tag === end($tags))
            {
                $tags_string .= $tag->getTag();
            }
            else
            {
                $tags_string .= $tag->getTag().',';
            }
        }
        return new JsonResponse($tags_string);
    }

}
