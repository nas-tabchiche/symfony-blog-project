<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index(): Response
    {

        $post = new \App\Entity\Post();
        $post->setTitle('Mon titre');
        $post->setContent('Mon contenu');


        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($post);

        $entityManager->flush();


        return $this->render('post/index.html.twig', [
            'post' => $post,
            'controller_name' => 'PostController',
        ]);
    }
}
