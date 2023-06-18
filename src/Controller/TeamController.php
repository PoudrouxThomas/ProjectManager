<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team')]
    public function index(ProjectRepository $projectRepo): Response
    {
        $projects = $projectRepo->getProjectsWithTeams();

        return $this->render('team/index.html.twig', compact('projects'));
    }
}
