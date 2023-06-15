<?php

namespace App\Controller;

use App\Entity\Task;
use App\Enums\Task\TaskState;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
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
                ->setProjectId($project);

            $taskRepo->save($task, true);

            return $this->redirectToRoute('app_project_show', ['id' => $projectId]);
        }

        return $this->render('task/create.html.twig', ['form' => $form, 'projectId' => $projectId]);
    }
}
