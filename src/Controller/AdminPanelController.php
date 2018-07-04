<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminPanelController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin_panel")
     */
    public function index()
    {
        return $this->redirectToRoute('article');
    }
}
