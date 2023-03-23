<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'profile.index')]
    #[Security("is_granted('ROLE_USER') and user === currentUser")]
    public function index(Request $request, User $currentUser, PostRepository $postRepo): Response
    {
        $limit = (int) $request->get('limit') | 10;

        return $this->render('profile/index.html.twig', [
            'posts' => $postRepo->findBy(['user' => $currentUser], ['createdAt' => 'ASC'], $limit),
            'limit' => $limit,
            'user' => $currentUser
        ]);
    }

    #[Route('/profile/edition/{id}', name: 'profile.edit')]
    #[Security("is_granted('ROLE_USER') and user === currentUser")]
    public function edit(Request $request, User $currentUser, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(UserType::class, $currentUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($currentUser);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre profile à été modifié avec succès !'
            );

            return $this->redirectToRoute('profile.index', ['id' => $currentUser->getId()]);
        }
        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $currentUser
        ]);
    }

    #[Route('/profile/suppression/{id}', name: 'profile.delete')]
    #[Security("is_granted('ROLE_USER') and user === currentUser")]
    public function delete(EntityManagerInterface $manager, User $currentUser): Response
    {
        $manager->remove($currentUser);
        $manager->flush();


        return $this->redirectToRoute('home.index');
    }
}
