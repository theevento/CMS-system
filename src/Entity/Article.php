<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="articles")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleComments", mappedBy="article")
     */
    private $articleComments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleTags", mappedBy="article")
     */
    private $articleTags;

    /**
     * @ORM\Column(type="text")
     */
    private $article;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleStatistics", mappedBy="article")
     */
    private $articleStatistics;


    public function __construct()
    {
        $this->articleComments = new ArrayCollection();
        $this->articleTags = new ArrayCollection();
        $this->articleStatistics = new ArrayCollection();
    }

    public function getUserId(): ?Users
    {
        return $this->users;
    }

    public function setUserId(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|ArticleComments[]
     */
    public function getArticleComments(): Collection
    {
        return $this->articleComments;
    }

    public function addArticleComment(ArticleComments $articleComment): self
    {
        if (!$this->articleComments->contains($articleComment)) {
            $this->articleComments[] = $articleComment;
            $articleComment->setArticleId($this);
        }

        return $this;
    }

    public function removeArticleComment(ArticleComments $articleComment): self
    {
        if ($this->articleComments->contains($articleComment)) {
            $this->articleComments->removeElement($articleComment);
            // set the owning side to null (unless already changed)
            if ($articleComment->getArticleId() === $this) {
                $articleComment->setArticleId(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|ArticleTags[]
     */
    public function getArticleTags(): Collection
    {
        return $this->articleTags;
    }

    public function addArticleTag(ArticleTags $articleTag): self
    {
        if (!$this->articleTags->contains($articleTag)) {
            $this->articleTags[] = $articleTag;
            $articleTag->setArticle($this);
        }

        return $this;
    }

    public function removeArticleTag(ArticleTags $articleTag): self
    {
        if ($this->articleTags->contains($articleTag)) {
            $this->articleTags->removeElement($articleTag);
            // set the owning side to null (unless already changed)
            if ($articleTag->getArticle() === $this) {
                $articleTag->setArticle(null);
            }
        }

        return $this;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ArticleStatistics[]
     */
    public function getArticleStatistics(): Collection
    {
        return $this->articleStatistics;
    }

    public function addArticleStatistic(ArticleStatistics $articleStatistic): self
    {
        if (!$this->articleStatistics->contains($articleStatistic)) {
            $this->articleStatistics[] = $articleStatistic;
            $articleStatistic->setArticle($this);
        }

        return $this;
    }

    public function removeArticleStatistic(ArticleStatistics $articleStatistic): self
    {
        if ($this->articleStatistics->contains($articleStatistic)) {
            $this->articleStatistics->removeElement($articleStatistic);
            // set the owning side to null (unless already changed)
            if ($articleStatistic->getArticle() === $this) {
                $articleStatistic->setArticle(null);
            }
        }

        return $this;
    }


}
