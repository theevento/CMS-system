<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use App\Service\MenuService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

class MenuController extends Controller
{
    /**
     * @Route("/admin/menu/add", name="menu")
     */
    public function index(Request $request, MenuService $menuService)
    {
        if($request->getMethod() === 'POST')
        {
            $result = $menuService->prepareMenu($request->get('name'), $request->get('page'), $request->get('new_tab'), $request->get('active'))
                ->addMenu();

            return new JsonResponse($result);
        }

        return $this->render('menu/index.html.twig');
    }


    /**
     * @Route("/admin/menu/edit", name="edit_list_menu")
     */
    public function editList(Request $request, MenuService $menuService)
    {
        if($request->getMethod() === 'POST')
        {
            $result = $menuService->updatePage($request->get('id'), $request->get('name'), $request->get('page'), $request->get('new_tab'), $request->get('active'));
            return new JsonResponse($result);
        }

        return $this->render('menu/edit_list.html.twig');
    }


    /**
     * @Route("/admin/menu/api/menu/", name="edit_list_api_menu")
     */

    public function editListApiMenu(SerializerInterface $serializer, MenuRepository $menuRepository)
    {
        return new JsonResponse($serializer->serialize($menuRepository->findMenu(), 'json'));
    }

}
