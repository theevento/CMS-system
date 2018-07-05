<?php

namespace App\Controller;

use App\Repository\PagesRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticPageController extends Controller
{
    /**
     * @Route("/static/{id}/{title}", name="static_page")
     */
    public function index($id, $title, PagesRepository $pagesRepository)
    {
        $page = $pagesRepository->findOneBy([
            'id' => $id,
            'name' => $title
        ]);

        if(!$page)
        {
            throw new NotFoundHttpException('Nie znaleziono strony');
        }

        return $this->render('static_page/index.html.twig', [
            'page' => $page
        ]);
    }
}
