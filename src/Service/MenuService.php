<?php

namespace App\Service;


use App\Entity\Menu;
use App\Entity\Pages;
use Doctrine\ORM\EntityManagerInterface;

class MenuService
{
    private $entityManager;
    private $menu;
    private $validator;
    private $user;

    public function __construct(EntityManagerInterface $entityManager, ValidatorService $validator)
    {
        $this->entityManager = $entityManager;
        $this->menu = new Menu();
        $this->validator = $validator;
    }

    public function prepareMenu(string $name, int $page, $new_tab, $active):self
    {
        $active = $active == 'true'? true: false;
        $new_tab = $new_tab == 'true'? true: false;
        $page = $this->entityManager->getRepository(Pages::class)->find($page);
        $this->menu->setName($name)
            ->setPage($page)
            ->setNewTab($new_tab)
            ->setActive($active);
        return $this;
    }

    public function addMenu():array
    {
        if(!$this->validator->setObject($this->menu)->ValidateObject())
        {
            return [
                'status' => false,
                'message' => $this->validator->GetErrorsAsArray()
            ];
        }

        $this->entityManager->persist($this->menu);
        $this->entityManager->flush();

        return [
            'status' => true,
            'message' => ['Pomyślnie dodano zakładkę.']
        ];
    }

    public function updatePage(int $id, string $name, int $page, $new_tab, $active):array
    {
        $active = $active == 'true'? true: false;
        $new_tab = $new_tab == 'true'? true: false;

        $this->menu =  $this->entityManager->getRepository(Menu::class)->find($id);
        $page = $this->entityManager->getRepository(Pages::class)->find($page);

        $this->menu->setName($name)
            ->setPage($page)
            ->setNewTab($new_tab)
            ->setActive($active);

        if(!$this->validator->setObject($this->menu)->ValidateObject())
        {
            return [
                'status' => false,
                'message' => $this->validator->GetErrorsAsArray()
            ];
        }

        $this->entityManager->persist($this->menu);
        $this->entityManager->flush();

        return [
            'status' => true,
            'message' => ['Pomyślnie zaaktualizowano zakładkę.']
        ];
    }
}