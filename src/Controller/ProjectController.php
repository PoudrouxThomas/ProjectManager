<?php

namespace App\Controller;

use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $projectRepo, Request $request): Response
    {
        $seeAllProjects = $request->query->get('seeAllProjects');
        if($this->getUser() && !$seeAllProjects)
        {   
            $pageName = "Your projects";
            $projects = $this->getUser()->getProjectsToDisplay();
        }
        else
        {
            $pageName = "Projects" ;
            $projects = $projectRepo->findAll();
        }

        return $this->render('project/index.html.twig', compact('projects', 'pageName'));
    }

    #[Route('project/{id<[0-9]+>}', name: 'app_project_show')]
    public function show(int $id, ProjectRepository $projectRepo): Response
    {
        $project = $projectRepo->find($id);

        return $this->render('project/show.html.twig',compact('project'));
    }

    #[Route('project/create', name: 'app_project_create')]
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

    #[Route('project/{id<[0-9]+>}/edit', name: 'app_project_edit')]
    public function edit(int $id, ProjectRepository $projectRepo, Request $request): Response
    {
        $project = $projectRepo->find($id);

        $form = $this->createForm(ProjectType::class, $project);

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
