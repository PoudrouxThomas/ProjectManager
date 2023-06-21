<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use App\Enums\Task\TaskState;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaskType extends AbstractType
{
    public function __construct(protected RequestStack $requestStack)
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $request = $this->requestStack->getCurrentRequest();

        $projectId = $request->attributes->get('id');

        $builder
            ->add('name', TextType:: class, [
                'constraints' => [
                    new NotBlank,
                    new Length(['min' => 5])
                ]
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank,
                    new Length(['min' => 15])
                ]
            ])
            ->add('state', EnumType::class, [
                'class' => TaskState::class,
                'choice_label' => function ($choice) {
                    return $choice->toString();
                }
            ])
            ->add('assigned_users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'query_builder' => function(UserRepository $userRepo) use ($projectId) {
                    return $userRepo->getTeamForProjectQuery($projectId);
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
