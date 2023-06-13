<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $projectRepo): Response
    {
        $projects = $projectRepo->findAll();

        return $this->render('project/index.html.twig', compact('projects'));
    }

    #[Route('project/{id<[0-9]+>}', name: 'app_project_show')]
    public function show(int $id, ProjectRepository $projectRepo): Response
    {
        $project = $projectRepo->find($id);

        return $this->render('project/show.html.twig',compact('project'));
    }

    #[Route('project/create', name: 'app_project_create')]
    public function create()
    {
        return $this->render('project/create.html.twig',[
            
        ]);
    }


}
