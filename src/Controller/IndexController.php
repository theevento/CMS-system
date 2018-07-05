<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleComments;
use App\Repository\ArticleCommentsRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleTagsRepository;
use App\Repository\MenuRepository;
use App\Repository\SettingsRepository;
use App\Repository\UsersRepository;
use App\Service\ArticleStatisticsService;
use App\Service\CommentsService;
use App\Service\CustomSerializer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\Serializer\SerializerInterface;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/home/{page}", name="homepage")
     */
    public function home($page = 0, SerializerInterface $serializer, ArticleRepository $articleRepository)
    {
        return $this->render('index/index.html.twig', [
            'article' => $serializer->serialize($articleRepository->findAllActiveArticleOnly($page), 'json'),
            'page' => $page,
            'size' => $serializer->serialize($articleRepository->findSizeOfAllActiveArticle()[0]['size'], 'json'),
        ]);
    }


    /**
     * @Route("/article/{id}/{title}/", name="post")
     */

    public function article(Request $request, CommentsService $commentsService, ArticleStatisticsService $articleStatistics, $id, ArticleCommentsRepository $commentsRepository, ArticleTagsRepository $tagsRepository, ArticleRepository $articleRepository, CustomSerializer $serializer)
    {
        $article = $articleRepository->findFullArticle($id);

        if(!$article)
        {
            throw $this->createNotFoundException('Nie znaleziono artykułu.');
        }

        $fullArticle = $articleRepository->find($id);

        $articleStatistics->addViewToDatabases($fullArticle);

        $form = $this->createFormBuilder(new ArticleComments())
            ->add('name', TextType::class, ['label' => 'Imię:'])
            ->add('email', EmailType::class, ['label' => 'E-mail:'])
            ->add('comment', TextareaType::class, ['label' => 'Komentarz:'])
            ->add('recaptcha', EWZRecaptchaType::class, ['mapped' => false, 'label' => 'Udowodnij, że nie jesteś robotem:',  'constraints' => array(new RecaptchaTrue())])
            ->add('save', SubmitType::class, ['label' => 'Dodaj komentarz'])
            ->getForm();

        $form->handleRequest($request);


        $comment = $commentsService->addComment($form, $fullArticle);

        if($comment["status"] === true)
        {
            return $comment["redirect"];
        }

        $author = $articleRepository->findArticleAuthorById($id);
        $comments = $commentsRepository->findCommentByArticleId($id);
        $tags = $tagsRepository->findTagsByIdArticle($id);
        $popularArticles = $articleRepository->findPopularArticles();

        return $this->render('index/blog.html.twig', [
            'form' => $form->createView(),
            'article' => $serializer->serialize($article, 'json'),
            'tags' => $serializer->serialize($tags, 'json'),
            'comments' => $serializer->serialize($comments, 'json'),
            'author' => $serializer->serialize($author, 'json'),
            'popular' => $serializer->serialize($popularArticles, 'json'),
        ]);
    }

    public function header(SettingsRepository $settingsRepository, MenuRepository $menuRepository)
    {
        return $this->render('index/header.html.twig', [
            'banner' => $settingsRepository->findAll(),
            'menu' => $menuRepository->findAll()
        ]);
    }

    public function footer(SettingsRepository $settingsRepository)
    {
        return $this->render('index/footer.html.twig', [
            'footer' => $settingsRepository->findAll()
        ]);
    }

    public function title(SettingsRepository $settingsRepository)
    {
        return $this->render('index/title.html.twig', [
            'title' => $settingsRepository->findAll()
        ]);
    }
}
