<?php

namespace App\Controller;

use App\Entity\Task;
use App\Enums\Task\TaskState;
use App\Form\CommentType;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskCommentRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/project/{projectId<[0-9]+>}/addTask', name: 'app_task_create')]
    public function create(int  $projectId, Request $request, TaskRepository $taskRepo, ProjectRepository $projectRepo): Response
    {
        $form = $this->createForm(TaskType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $task = $form->getData();

            $project = $projectRepo->find($projectId);

            $task
                ->setState(TaskState::CREATED)
                ->setProject($project);

            $taskRepo->save($task, true);
            
            $this->addFlash('success', 'Your task has been added to the project !');

            return $this->redirectToRoute('app_project_show', ['id' => $projectId]);
        }

        return $this->render('task/create.html.twig', ['form' => $form, 'projectId' => $projectId]);
    }

    #[Route('/task/{taskId<[0-9]+>}', name: 'app_task_show')]
    public function show(int $taskId, Request $request, TaskRepository $taskRepo, TaskCommentRepository $commentRepo)
    {
        $task = $taskRepo->find($taskId);

        $form = $this->createForm(CommentType::class);

        $form->handleRequest($request);

        if($this->getUser() && $form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();

            $comment->setAuthor($this->getUser());
            $comment->setTask($task);

            $commentRepo->save($comment, true);

            $this->addFlash('success', 'Your comment has been added !');

            return $this->redirectToRoute('app_task_show', ['taskId' => $task->getId()]);
        }

        return $this->render('task/show.html.twig', compact('task', 'form'));
    }
}
