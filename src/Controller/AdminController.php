<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class AdminController extends AbstractController
{
    /////////////////////////////
    ///       ADMIN HOME      ///
    /////////////////////////////
    /**
     * @Route("admin/articles", name="admin_articles_home")
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        return $this->render('Admin/Article/index.html.twig', array(
            'posts' => $posts
        ));
    }

    /////////////////////////////
    ///         CREATE        ///
    /////////////////////////////

    /**
     * @Route("admin/article/new/", name="admin_article_new")
     * @param Request $request
     * @return Response
     */
    public function adminNewArticleAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('content', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('author', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Article',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ))

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'spremljeno u bazu'
            );

            return $this->redirectToRoute('admin_articles_home');
        }

        return $this->render('Admin/Article/newArticle.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /////////////////////////////
    ///         UPDATE        ///
    /////////////////////////////

    /**
     * @Route("admin/article/edit/{id}", name="admin_article_edit")
     */
    public function adminEditArticleAction(Request $request, $id)
    {

        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('content', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('author', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Update Article',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ))

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'spremljeno u bazu'
            );

            return $this->redirectToRoute('admin_articles_home');
        }

        return $this->render('Admin/Article/editArticle.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /////////////////////////////
    ///         DELETE        ///
    /////////////////////////////

    /**
     * @Route("admin/article/delete/{id}", name="admin_article_delete")
     */
    public function adminDeleteArticleAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Article::class)->find($id);
//dump($post); die();
        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Article is deleted'
        );

        return $this->redirectToRoute('admin_articles_home');

    }
}






