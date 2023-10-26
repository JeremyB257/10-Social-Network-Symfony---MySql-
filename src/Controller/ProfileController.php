<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Form\UserType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'profile.index')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, User $currentUser, PostRepository $postRepo, EntityManagerInterface $manager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('profile.index', ['id' => $currentUser->getId()]);
        }
        $limit = (int) ($request->get('limit') ?? 10);

        return $this->render('profile/index.html.twig', [
            'posts' => $postRepo->findBy(['user' => $currentUser], ['createdAt' => 'DESC'], $limit),
            'limit' => $limit,
            'user' => $currentUser,
            'form' => $form
        ]);
    }

    #[Route('/post/suppression/{id}', name: 'post.delete')]
    #[Security("is_granted('ROLE_USER') and user === post.getUser()")]
    public function deletePost(EntityManagerInterface $manager, Post $post): Response
    {
        $manager->remove($post);
        $manager->flush();
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        return $this->redirectToRoute('profile.index', ['id' => $user->getId()]);
    }


    #[Route('/profile/edition/{id}', name: 'profile.edit')]
    #[Security("is_granted('ROLE_USER') and user === currentUser")]
    public function edit(Request $request, User $currentUser, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(UserType::class, $currentUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugger = new AsciiSlugger();
            $currentUser->setPseudo($slugger->slug($currentUser->getPseudo()));
            //upload
            /** @var UploadedFile */
            $imgFile = $form->get('imgFile')->getData();
            if ($imgFile) {
                $fileName = uniqid() . '.' . $imgFile->guessExtension();
                $imgFile->move($this->getParameter('profile_uploads'), $fileName);
                //stocker le nom du fichier dans la BDD
                $currentUser->setImg($fileName);
            }
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
    public function deleteUser(EntityManagerInterface $manager, User $currentUser): Response
    {
        $manager->remove($currentUser);
        $manager->flush();


        return $this->redirectToRoute('home.index');
    }
}
