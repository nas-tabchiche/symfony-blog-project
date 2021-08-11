<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index(): Response
    {
        $comm = new \App\Entity\Comment();
        $comm->setUsername('Mon nom d\'utilisateur');
        $comm->setContent('Mon contenu');

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($comm);

        $entityManager->flush();

        return $this->render('comment/index.html.twig', [
            'comm' => $comm,
            'controller_name' => 'CommentController'
        ]);
    }
}
