<?php

namespace App\Controller;

use App\Repository\CommentRepository;
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
    public function index(PostRepository $postRepo, Request $request, CommentRepository $comRepo): Response
    {

        $limit = (int) $request->get('limit') | 10;

        return $this->render('home/index.html.twig', [
            'posts' => $postRepo->findBy([], ['createdAt' => 'DESC'], $limit),
            'limit' => $limit,
        ]);
    }
}
