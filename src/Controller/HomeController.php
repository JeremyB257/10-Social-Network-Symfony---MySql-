<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    #[Route('/accueil', name: 'home.index')]
    public function index(PostRepository $postRepo, Request $request): Response
    {

        $limit = (int) ($request->get('limit') ?? 10);
        $posts = $postRepo->findBy([], ['createdAt' => 'DESC'], $limit);

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'limit' => $limit,
        ]);
    }

    #[Route('/like/{id}', name: 'home.like')]
    public function like(Post $post, EntityManagerInterface $manager): Response
    {
        $post->addUsersLike($this->getUser());
        $manager->persist($post);
        $manager->flush();
        return $this->redirectToRoute('home.index');
    }
    #[Route('/unlike/{id}', name: 'home.unlike')]
    public function unlike(Post $post, EntityManagerInterface $manager): Response
    {

        $post->removeUsersLike($this->getUser());
        $manager->persist($post);
        $manager->flush();
        return $this->redirectToRoute('home.index');
    }
}
