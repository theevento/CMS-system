<?php

namespace App\Service;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class CommentsService
{

    private $request;
    private $entityManager;
    private $serializer;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, CustomSerializer $serializer)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }


    public function addComment(Form $form, Article $article)
    {
        if($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();
            $comment->setIp($this->request->getClientIp())
                ->setDate(new \DateTime("now"))
                ->setArticleId($article)
                ->setActive(true);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();


            return [
                'status' => true,
                'redirect' => new RedirectResponse('/article/' . $article->getId() . '/' . $article->getTitle() . '/#comments')
            ];

        }
    }
}