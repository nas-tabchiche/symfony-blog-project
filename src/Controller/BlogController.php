<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\PostType;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    public function index(): Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(
            ['isPublished' => true],
            ['publishedAt' => 'desc']
        );

        return $this->render('blog/index.html.twig', ['posts' => $posts]);
    }

    public function index_admin(): Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(
            ['isPublished' => true],
            ['publishedAt' => 'desc']
        );

        return $this->render('blog/index_admin.html.twig', ['posts' => $posts]);
    }

    public function liste_posts(): Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(
            ['isPublished' => true],
            ['publishedAt' => 'desc']
        );

        return $this->render('blog/liste_posts.html.twig', ['posts' => $posts]);
    }

    public function liste_categories(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('blog/liste_categories.html.twig', ['categories' => $categories]);
    }

    public function add(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTime());
            $post->setSlug($post->getTitle());

            if ($post->getIsPublished()) {
                $post->setPublishedAt(new \DateTime());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return new Response('Post was submitted.');
        }

        return $this->render('blog/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function show(Post $post, Request $request)
    {
        $comm = new Comment();
        $form = $this->createForm(CommentType::class, $comm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comm->setCreatedAt(new \DateTime());
            $comm->setValid(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comm);
            $em->flush();

            return new Response('Comment was submitted.');
        }

        return $this->render('blog/show.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    public function edit(Post $post, Request $request)
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUpdatedAt(new \DateTime());

            if ($post->getIsPublished()) {
                $post->setPublishedAt(new \DateTime());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return new Response("Le post a été modifié avec succès.");
        }

        return $this->render('blog/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entite = $em->getRepository(Post::class)->find($id);
        $em->remove($entite);
        $em->flush();

        return new Response("Le post n° " . $id . " a été supprimé avec succès");
    }

    public function delete_comm($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entite = $em->getRepository(Comment::class)->find($id);
        $em->remove($entite);
        $em->flush();

        return new Response("Le commentaire n° " . $id . " a été supprimé avec succès");
    }

    public function ajout_comm(Request $request)
    {
        $comm = new Comment();
        $form = $this->createForm(CommentType::class, $comm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comm->setCreatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comm);
            $em->flush();

            return new Response('Comment was submitted.');
        }

        return $this->render('blog/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function liste_comm(): Response
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();

        return $this->render('blog/liste_comm.html.twig', ['comments' => $comments]);
    }
}
