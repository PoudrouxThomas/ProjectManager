<?php

namespace App\Entity;

use App\Enums\Task\TaskState;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "tasks")]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: "task_state", length: 255, options: ["default" => "CREATED"])]
    private ?TaskState $state = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getState(): ?TaskState
    {
        return $this->state;
    }

    public function setState(?TaskState $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getProjectId(): ?Project
    {
        return $this->project_id;
    }

    public function setProjectId(?Project $project_id): static
    {
        $this->project_id = $project_id;

        return $this;
    }
}
