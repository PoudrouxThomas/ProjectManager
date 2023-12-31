<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: 'users')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Task::class)]
    private Collection $tasksCreated;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: TaskComment::class)]
    private Collection $taskComments;

    #[ORM\OneToMany(mappedBy: 'project_manager', targetEntity: Project::class)]
    private Collection $managedProjects;

    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'assigned_users')]
    private Collection $assignedTasks;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'team_members')]
    private Collection $assignedProjects;

    public function __construct()
    {
        $this->tasksCreated = new ArrayCollection();
        $this->taskComments = new ArrayCollection();
        $this->managedProjects = new ArrayCollection();
        $this->assignedTasks = new ArrayCollection();
        $this->assignedProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasksCreated;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasksCreated->contains($task)) {
            $this->tasksCreated->add($task);
            $task->setAuthor($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasksCreated->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getAuthor() === $this) {
                $task->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TaskComment>
     */
    public function getTaskComments(): Collection
    {
        return $this->taskComments;
    }

    public function addTaskComment(TaskComment $taskComment): static
    {
        if (!$this->taskComments->contains($taskComment)) {
            $this->taskComments->add($taskComment);
            $taskComment->setAuthor($this);
        }

        return $this;
    }

    public function removeTaskComment(TaskComment $taskComment): static
    {
        if ($this->taskComments->removeElement($taskComment)) {
            // set the owning side to null (unless already changed)
            if ($taskComment->getAuthor() === $this) {
                $taskComment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->managedProjects;
    }

    public function addManagedProject(Project $project): static
    {
        if(!in_array('ROLE_PROJECT_MANAGER', $this->roles, true))
        {
            throw new \Exception("Can't give a project to a user who's not a project manager.");
        }

        if (!$this->managedProjects->contains($project)) {
            $this->managedProjects->add($project);
            $project->setProjectManager($this);
        }

        return $this;
    }

    public function removeManagedProject(Project $project): static
    {
        if ($this->managedProjects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getProjectManager() === $this) {
                $project->setProjectManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getAssignedTasks(): Collection
    {
        return $this->assignedTasks;
    }

    public function addAssignedTask(Task $assignedTasks): static
    {
        if (!$this->assignedTasks->contains($assignedTasks)) {
            $this->assignedTasks->add($assignedTasks);
            $assignedTasks->addAssignedUser($this);
        }

        return $this;
    }

    public function removeAssignedTask(Task $assignedTasks): static
    {
        if ($this->assignedTasks->removeElement($assignedTasks)) {
            $assignedTasks->removeAssignedUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getAssignedProjects(): Collection
    {
        return $this->assignedProjects;
    }

    public function addAssignedProject(Project $assignedProject): static
    {
        if (!$this->assignedProjects->contains($assignedProject)) {
            $this->assignedProjects->add($assignedProject);
            $assignedProject->addTeamMember($this);
        }

        return $this;
    }

    public function removeAssignedProject(Project $assignedProject): static
    {
        if ($this->assignedProjects->removeElement($assignedProject)) {
            $assignedProject->removeTeamMember($this);
        }

        return $this;
    }

    public function getProjectsToDisplay()
    {
        return array_merge($this->assignedProjects->toArray(), $this->managedProjects->toArray());
    }
}
