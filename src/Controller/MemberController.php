<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class MemberController extends AbstractController
{
    #[Route('/membres', name: 'users.index')]
    public function index(UserRepository $userRepo): Response
    {
        return $this->render('member/index.html.twig', [
            'users' => $userRepo->findAll()
        ]);
    }
}
