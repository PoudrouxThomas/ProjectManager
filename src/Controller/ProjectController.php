<?php

namespace App\Controller;

use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ["GET"])]
    public function index(ProjectRepository $projectRepo, Request $request): Response
    {
        if(!$this->getUser())
            return $this->redirectToRoute('app_projects');

        $pageName = "Your projects";
        $projects = $this->getUser()->getProjectsToDisplay();

        return $this->render('project/index.html.twig', compact('projects', 'pageName'));
    }

    #[Route('/projects', name: 'app_projects', methods: ["GET"])]
    public function showallProjects(ProjectRepository $projectRepo, Request $request): Response
    {
        $pageName = "Projects" ;
        $projects = $projectRepo->findAll();

        return $this->render('project/index.html.twig', compact('projects', 'pageName'));
    }

    #[Route('project/{id<[0-9]+>}', name: 'app_project_show', methods: ["GET"])]
    public function show(int $id, ProjectRepository $projectRepo): Response
    {
        $project = $projectRepo->find($id);

        return $this->render('project/show.html.twig',compact('project'));
    }

    #[Route('project/create', name: 'app_project_create', methods: ["GET", "POST"])]
    public function create(Request $request, ProjectRepository $projectRepo)
    {
        $form = $this->createForm(ProjectType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $project  = $form->getData();

            $projectRepo->save($project, true);

            $this->addFlash('success', 'Project created !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('project/create.html.twig', compact('form'));
    }

    #[Route('project/{id<[0-9]+>}/edit', name: 'app_project_edit', methods: ["GET", "PUT"])]
    public function edit(int $id, ProjectRepository $projectRepo, Request $request): Response
    {
        $project = $projectRepo->find($id);

        // TeamManagers can only edit the project manager assigned to the project
        if($this->isGranted('ROLE_TEAM_MANAGER'))
        {
            $form = $this->createFormBuilder($project)
                ->add('project_manager', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => 'username',
                    'query_builder' => function(UserRepository $userRepo) {
                        return $userRepo->getProjectManagersQuery();
                    }
                ])
                ->setMethod("PUT")
                ->getForm();
        }
        // Else, ProjectManagers can edit all the project except the project manager assigned to the project
        else 
        {
            $form = $this->createForm(ProjectType::class, $project, ['method' => 'PUT']);
        }
        

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $projectRepo->save($project, true);

            $this->addFlash('success', 'The project has been edited !');
            
            return $this->redirectToRoute('app_project_show', [ 'id' => $project->getId() ]);
        }

        return $this->render('project/edit.html.twig',compact('project', 'form'));
    }


}
