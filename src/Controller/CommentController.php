<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

#[IsGranted('ROLE_USER')]
class CommentController extends AbstractController
{
    #[Route('/comment/create/{id}', name: 'comment.create', methods: ['GET', 'POST'])]
    public function index(Request $request, Post $post,  EntityManagerInterface $manager, ValidatorInterface $validator)
    {

        $content = $request->get('content');
        $errors = $validator->validate($content, [
            new NotBlank
        ]);

        if (count($errors) > 0) {
            throw new AccessDeniedHttpException('No data sent');
        }


        $comment = new Comment();
        $comment->setContent($content)
            ->setUser($this->getUser())
            ->setPost($post);

        $manager->persist($comment);
        $manager->flush();


        return $this->redirectToRoute('home.index');
    }


    #[Route('/commentaire/suppression/{id}', name: 'comment.delete')]
    #[Security("is_granted('ROLE_USER') and user === comment.getUser()")]
    public function deleteComment(EntityManagerInterface $manager, Comment $comment): Response
    {
        $manager->remove($comment);
        $manager->flush();


        return $this->redirectToRoute('home.index');
    }
}
