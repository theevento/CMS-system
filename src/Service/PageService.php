<?php

namespace App\Service;


use App\Entity\Pages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PageService
{
    private $entityManager;
    private $page;
    private $validator;
    private $user;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, ValidatorService $validator)
    {
        $this->entityManager = $entityManager;
        $this->page = new Pages();
        $this->user = $tokenStorage->getToken()->getUser();
        $this->validator = $validator;
    }

    public function preparePage(string $name, string $type, string $page):self
    {
        $this->page->setName($name)
            ->setPage($page)
            ->setType($type)
            ->setUsers($this->user);
        return $this;
    }

    public function addPage():array
    {
        if(!$this->validator->setObject($this->page)->ValidateObject())
        {
            return [
                'status' => false,
                'message' => $this->validator->GetErrorsAsArray()
            ];
        }

        $this->entityManager->persist($this->page);
        $this->entityManager->flush();

        return [
            'status' => true,
            'message' => ['Pomyślnie dodano stronę']
        ];
    }

    public function updatePage(int $id, string $name, string $type, string $page):array
    {
       $this->page =  $this->entityManager->getRepository(Pages::class)->find($id);
        $this->page->setUsers($this->user)
            ->setPage($page)
            ->setType($type)
            ->setName($name);

        if(!$this->validator->setObject($this->page)->ValidateObject())
        {
            return [
                'status' => false,
                'message' => $this->validator->GetErrorsAsArray()
            ];
        }

        $this->entityManager->persist($this->page);
        $this->entityManager->flush();

        return [
            'status' => true,
            'message' => ['Pomyślnie zaaktualizowano stronę.']
        ];
    }
}