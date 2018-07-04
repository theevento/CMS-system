<?php

namespace App\Controller;

use App\Repository\PagesRepository;
use App\Service\PageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

class PageController extends Controller
{
    /**
     * @Route("/admin/page/add", name="page")
     */
    public function index(Request $request, PageService $pageService)
    {
        if($request->getMethod() === 'POST')
        {
            $result = $pageService->preparePage($request->get('name'), $request->get('type'),$request->get('page'))
                ->addPage();

            return new JsonResponse($result);
        }
        return $this->render('page/index.html.twig');
    }


    /**
     * @Route("/admin/page/edit", name="edit_list_page")
     */
    public function editList(Request $request, PageService $pageService)
    {
        if($request->getMethod() === 'POST')
        {
            $result = $pageService->updatePage($request->get('id'), $request->get('name'), $request->get('type'), $request->get('page'));

            return new JsonResponse($result);
        }

        return $this->render('page/edit_list.html.twig');
    }

    /**
     * @Route("/admin/api/page/", name="edit_list_api_page")
     */

    public function editListApiPage(SerializerInterface $serializer, PagesRepository $pagesRepository)
    {
        return new JsonResponse($serializer->serialize($pagesRepository->findAllPages(), 'json'));
    }

}
