<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Wish;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CommentController extends AbstractController
{
    #[Route('/wishes/{id}/comments/create', name: 'comment_create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(?Wish $wish, Request $request, EntityManagerInterface $em): Response
    {
        if(!$wish){
            throw $this->createNotFoundException("This wish does not exist !");
        }
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setWish($wish);
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $em->persist($comment);
            $em->flush();
            $this->addFlash("success", "Comment created !");
            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);
        }
        return $this->render('comment/create.html.twig', [
            'commentForm' => $commentForm,
        ]);
    }

    #[Route('/wishes/comments/{id}', name: 'comment_update', requirements: ['id'=>'\d+'],methods: ['GET', 'POST'])]
    #[IsGranted(
        attribute: new Expression('user === subject'),
        subject: new Expression('args["comment"].getUser()'),
    )]
    public function update(?Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        if(!$comment){
            throw $this->createNotFoundException("This comment does not exist !");
        }
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $comment->setDateUpdated(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash("success", "Comment successfully updated !");
            return $this->redirectToRoute('app_wish_detail', ['id' => $comment->getWish()->getId()]);
        }
        return $this->render('comment/create.html.twig', [
            'commentForm' => $commentForm,
        ]);
    }

    #[Route('/wishes/comments/{id}/delete', name: 'comment_delete',
        requirements: ['id'=>'\d+'], methods: ['GET'])]
    #[IsGranted(
        attribute: new Expression('user === subject or "ROLE_ADMIN" in role_names'),
        subject: new Expression('args["comment"].getUser()'),
    )]
    public function delete(?Comment $comment,Request $request, EntityManagerInterface $em): Response
    {
        if(!$comment){
            throw $this->createNotFoundException();
        }
        if($this->isCsrfTokenValid('delete-'.$comment->getId(), $request->get('token'))) {
            try {
                $em->remove($comment);
                $em->flush();
                $this->addFlash('success', 'The comment has been deleted.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'The comment cannot be deleted.');
            }
        }else{
            $this->addFlash('danger', 'The comment cannot be deleted: Pb Token');
        }
        return $this->redirectToRoute('app_wish_detail', ['id' => $comment->getWish()->getId()]);
    }
}
