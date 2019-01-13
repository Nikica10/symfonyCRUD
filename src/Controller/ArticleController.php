<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
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
    public function singleArticleAction($id)
    {

        $post = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['id' => $id]);


        return $this->render('Home/singleArticle.html.twig', array(
            'post' => $post
        ));
    }
}