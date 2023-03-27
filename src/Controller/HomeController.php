<?php

namespace App\Controller;

use App\Repository\PostRepository;
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

        $limit = (int) $request->get('limit') | 10;
        $posts = $postRepo->findBy([], ['createdAt' => 'DESC'], $limit);

        for ($i = 0; $i <= count($posts[0]->getUsersLike()); $i++) {
            if ($this->getUser() == $posts[0]->getUsersLike()[$i]) {

                $userLike = true;
            } else {

                $userLike = false;
            }
            dump($userLike);
        }
        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'limit' => $limit,
        ]);
    }
}
