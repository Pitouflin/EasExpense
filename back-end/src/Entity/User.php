<?php

// src/Entity/User.php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["vehicle:read", "user:read", "expenseReport:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["vehicle:read", "user:read", "expenseReport:read"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user:read"])]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user:read"])]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: ExpenseReport::class, mappedBy: 'userId')]
    #[Groups(["user:read"])]
    private Collection $expenseReportsList;

    #[ORM\OneToMany(targetEntity: Vehicle::class, mappedBy: 'userId')]
    #[Groups(["user:read"])]
    private Collection $vehicleList;

    public function __construct()
    {
        $this->expenseReportsList = new ArrayCollection();
        $this->vehicleList = new ArrayCollection();
    }

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

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return Collection<int, ExpenseReport>
     */
    public function getExpenseReportsList(): Collection
    {
        return $this->expenseReportsList;
    }

    public function addExpenseReportsList(ExpenseReport $expenseReport): static
    {
        if (!$this->expenseReportsList->contains($expenseReport)) {
            $this->expenseReportsList->add($expenseReport);
            $expenseReport->setUserId($this);
        }

        return $this;
    }

    public function removeExpenseReportsList(ExpenseReport $expenseReport): static
    {
        if ($this->expenseReportsList->removeElement($expenseReport)) {
            if ($expenseReport->getUserId() === $this) {
                $expenseReport->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicleList(): Collection
    {
        return $this->vehicleList;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicleList->contains($vehicle)) {
            $this->vehicleList->add($vehicle);
            $vehicle->setUserId($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicleList->removeElement($vehicle)) {
            if ($vehicle->getUserId() === $this) {
                $vehicle->setUserId(null);
            }
        }

        return $this;
    }

    #[ORM\Column(type: 'json')]
    #[Groups(["user:read"])]
    private array $roles = [];

    public function getRoles(): array
    {
        // Cette méthode doit retourner un tableau de rôles attribués à l'utilisateur.
        // Par défaut, Symfony utilise un tableau de chaînes de caractères, avec au minimum 'ROLE_USER'.
        $roles = $this->roles;
        // Garantir que chaque utilisateur a au moins le rôle ROLE_USER.
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    public function eraseCredentials(): void
    {
        // No sensitive data to erase
        // The method body is empty because Symfony does not use this method for security
    }

    public function getUserIdentifier() :string
    {
        return $this->login; // Replace with your own unique identifier
    }

}
