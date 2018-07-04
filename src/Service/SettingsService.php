<?php

namespace App\Service;


use App\Entity\Settings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;

class SettingsService
{
    private $formBuilder;
    private $entityManager;
    private $form;

    public function __construct(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager)
    {
        $this->formBuilder = $formBuilder;
        $this->entityManager = $entityManager;
    }

    public function buildForm()
    {
        $this->form = $this->formBuilder->createFormBuilder($settings)
            ->add('title', TextType::class, ['label' => 'Tytuł:'])
            ->add('text_logo', TextType::class, ['label' => 'Tekst banneru:'])
            ->add('footer', TextType::class, ['label' => 'Stopka:'])
            ->add('save', SubmitType::class, ['label' => 'Zapisz ustawienia'])
            ->getForm();
    }

    public function getFrom()
    {
        $settings = $this->entityManager->getRepository(Settings::class)->find(1);
        if(!$settings)
        {
            $settings = new Settings();
        }


    }


}