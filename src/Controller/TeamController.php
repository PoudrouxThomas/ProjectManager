<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team')]
    public function index(ProjectRepository $projectRepo): Response
    {
        $projects = $projectRepo->getProjectsWithTeams();

        return $this->render('team/index.html.twig', compact('projects'));
    }
}
