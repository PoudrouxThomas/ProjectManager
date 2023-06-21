<?php

namespace App\Controller;

use App\Entity\TaskComment;
use App\Form\CommentType;
use App\Repository\TaskCommentRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/{commentId<[0-9]+>}/edit', name: 'app_comment_edit', methods: ['GET', 'PUT'])]
    public function edit(int $commentId, TaskCommentRepository $taskCommentRepo, Request $request): Response
    {
        $comment = $taskCommentRepo->find($commentId);
        $form =  $this->createForm(CommentType::class, $comment, ['method' =>  'PUT']);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();

            $taskCommentRepo->save($comment, true);

            $this->addFlash('success', 'Your comment has been edited.');

            return $this->redirectToRoute('app_task_show', ['taskId' => $comment->getTask()->getId()]);

        }


        return $this->render('comment/index.html.twig', compact('form', 'comment'));
    }
}
