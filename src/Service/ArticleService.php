<?php

namespace App\Service;


use App\Entity\Article;
use App\Entity\ArticleTags;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleService
{
    private $fileUploader;
    private $user;
    private $article;
    private $tags;
    private $entityManager;
    private $validator;
    private $uploadedFile;


    public function __construct(FileUploader $fileUploader, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager, ValidatorService $validatorService)
    {
        $this->fileUploader = $fileUploader;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->article = new Article();
        $this->entityManager = $entityManager;
        $this->validator = $validatorService;
    }

    public function prepareArticle(string $title, $active, string $article, $uploadedFile, $description):self
    {
        $this->uploadedFile = $uploadedFile;
        $active = $active === 'true'? true: false;
        $this->article->setUserId($this->user)
            ->setTitle($title)
            ->setDate(new \DateTime("now"))
            ->setArticle($article)
            ->setActive($active)
            ->setImage($uploadedFile)
            ->setDescription($description);
        return $this;
    }

    public function prepareTags(string $tags):self
    {
        $split_tag = explode(',', $tags);
        $this->tags = array_map('trim', $split_tag);
        return $this;
    }

    public function AddArticle():array
    {
        if(!$this->validator->setObject($this->article)->ValidateObject())
        {
            return [
                'status' => false,
                'message' => $this->validator->GetErrorsAsArray()
            ];
        }
        $realname = $this->fileUploader->upload($this->uploadedFile);
        $this->article->setImage($realname);
        $this->entityManager->persist($this->article);

        foreach ($this->tags as $tag)
        {
            $articleTags = new ArticleTags();
            $articleTags->setArticle($this->article)->setTag($tag);
            $this->entityManager->persist($articleTags);
        }

        $this->entityManager->flush();

        return [
            'status' => true,
            'message' => ['Pomyślnie dodano artykuł']
        ];
    }

    public function UpdateArticle($id, string $title, $active, string $article, $uploadedFile, $description):array
    {
        $active = $active === 'true'? true: false;
        $article_object = $this->entityManager->getRepository(Article::class)->find($id);
        $article_tags = $this->entityManager->getRepository(ArticleTags::class)->findTagsById($id);

        foreach ($article_tags as $article_tags_loop)
        {
            $article_object->removeArticleTag($article_tags_loop);
        }

        $article_object->setTitle($title)
            ->setArticle($article)
            ->setActive($active)
            ->setDescription($description);


        if(!is_null($uploadedFile))
        {
            $this->uploadedFile = $uploadedFile;
            $article_object->setImage($uploadedFile);
        }


        if(!$this->validator->setObject($article_object)->ValidateObject())
        {
            return [
                'status' => false,
                'message' => $this->validator->GetErrorsAsArray()
            ];
        }

        if(!is_null($uploadedFile))
        {
            $realname = $this->fileUploader->upload($this->uploadedFile);
            $article_object->setImage($realname);
        }

        $this->entityManager->persist($article_object);

        foreach ($this->tags as $tag)
        {
            $articleTags = new ArticleTags();
            $articleTags->setArticle($article_object)->setTag($tag);
            $this->entityManager->persist($articleTags);
        }

        $this->entityManager->flush();

        return [
            'status' => true,
            'message' => ['Pomyślnie zaaktualizowano artykuł']
        ];

    }


}