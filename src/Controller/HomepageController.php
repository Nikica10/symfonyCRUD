<?php
/**
 * Created by PhpStorm.
 * User: Ivana i Nino
 * Date: 10-Jan-19
 * Time: 20:11
 */

namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends Controller
{
    /////////////////////////////
    ///         HOME          ///
    /////////////////////////////
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction()
    {

        $posts = $this->get(ArticleRepository::class)->findBy([],['id'=>'DESC'], 3);


        return $this->render('Home/index.html.twig', array(
            'posts' => $posts
        ));
    }

}