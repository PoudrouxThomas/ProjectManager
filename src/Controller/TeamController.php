<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TeamController extends AbstractController
{
    #[Route('/team', name: 'app_team', methods: ["GET"])]
    public function index(ProjectRepository $projectRepo): Response
    {
        $projects = $projectRepo->getProjectsWithTeams();

        return $this->render('team/index.html.twig', compact('projects'));
    }

    #[Route('/team/{id<[0-9]+>}/edit', name: 'app_team_edit', methods: ["GET", "PUT"])]
    public function edit(int $id, ProjectRepository $projectRepo, UserRepository $userRepo, Request $request): Response
    {
        $project = $projectRepo->getProjectWithTeam($id);

        $form = $this->createFormBuilder($project)
            ->add('team_members', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'query_builder' => function(UserRepository $userRepo) {
                    return $userRepo->getUsersQuery();
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true
            ])
            ->setMethod("PUT")
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $projectRepo->save($project, true);

            $this->addFlash('success', 'The team has been modified');

            return $this->redirectToRoute('app_team');
        }

        return $this->render('team/edit.html.twig', compact('form', 'project'));
    }
}
