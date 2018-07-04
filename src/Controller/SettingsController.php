<?php

namespace App\Controller;

use App\Entity\Settings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingsController extends Controller
{
    /**
     * @Route("/admin/settings", name="settings")
     */

    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $settings = $entityManager->getRepository(Settings::class)->find(1);
        if(!$settings)
        {
            $settings = new Settings();
        }

        $form = $this->createFormBuilder($settings)
            ->add('title', TextType::class, ['label' => 'Tytuł:'])
            ->add('text_logo', TextType::class, ['label' => 'Tekst banneru:'])
            ->add('footer', TextType::class, ['label' => 'Stopka:'])
            ->add('save', SubmitType::class, ['label' => 'Zapisz ustawienia'])
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $settings = $form->getData();


            $entityManager->persist($settings);
            $entityManager->flush();

            return $this->render('settings/index.html.twig', [
                'form' => $form->createView(),
                'success' => true
            ]);
        }

        return $this->render('settings/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
