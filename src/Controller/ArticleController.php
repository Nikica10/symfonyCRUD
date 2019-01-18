<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comments;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;





class ArticleController extends Controller
{

    private $paginationLimit = 2;

    /////////////////////////////
    ///         HOME          ///
    /////////////////////////////
    /**
     * @Route("articles/{page}", name="articles")
     * @param $page
     * @return Response
     */
    public function articlesAction($page = 1)
    {

        $posts = $this->get(ArticleRepository::class)->getAllPostsPaginate($page, $this->paginationLimit);

        # Total fetched (ie: `5` posts)
        $totalPostsReturned = $posts->getIterator()->count();

        # Count of ALL posts (ie: `20` posts)
        $totalPosts = $posts->count();

        # ArrayIterator
        $iterator = $posts->getIterator();


        return $this->render('Articles/index.html.twig', array(
            'posts' => $posts,
            'thisPage' => $page,
            'maxPages' => ($posts->count() / $this->paginationLimit)
        ));
    }

    /////////////////////////////
    ///      SINGLE POST      ///
    /////////////////////////////
    /**
     * @Route("/article/single/{id}", name="single_article")
     * @param $id
     * @return Response
     */
    public function singleArticleAction(Request $request, $id)
    {

        $post = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['id' => $id]);

        // comments
        $entityManager = $this->getDoctrine()->getManager();
        $newComment = new Comments();



        $form = $this->createFormBuilder($newComment)
            ->add('title', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('comment', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Comment',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newComment = $form->getData()->setArticle($post);

            $entityManager->persist($newComment);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Komentar je spremljen u bazu'
            );

        }

        // display comments
//        $comments = $this->getDoctrine()
//            ->getRepository(Comments::class)
//            ->findAll();

        $entityManager = $this->getDoctrine()->getManager();

        $query = $entityManager->createQuery(
            'SELECT c
        FROM App\Entity\Comments c
        WHERE c.article = :articleId'
        )->setParameter('articleId', $id);


        return $this->render('Home/singleArticle.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
            'comments' => $query->execute()
        ));
    }
}