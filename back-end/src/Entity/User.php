<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var Collection<int, ExpenseReport>
     */
    #[ORM\OneToMany(targetEntity: ExpenseReport::class, mappedBy: 'userId')]
    private Collection $expenseReportsList;

    /**
     * @var Collection<int, Vehicle>
     */
    #[ORM\OneToMany(targetEntity: Vehicle::class, mappedBy: 'userId')]
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

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Collection<int, ExpenseReport>
     */
    public function getExpenseReportsList(): Collection
    {
        return $this->expenseReportsList;
    }

    public function addExpenseReportsList(ExpenseReport $expenseReportsList): static
    {
        if (!$this->expenseReportsList->contains($expenseReportsList)) {
            $this->expenseReportsList->add($expenseReportsList);
            $expenseReportsList->setUserId($this);
        }

        return $this;
    }

    public function removeExpenseReportsList(ExpenseReport $expenseReportsList): static
    {
        if ($this->expenseReportsList->removeElement($expenseReportsList)) {
            // set the owning side to null (unless already changed)
            if ($expenseReportsList->getUserId() === $this) {
                $expenseReportsList->setUserId(null);
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

    public function addVehicleList(Vehicle $vehicleList): static
    {
        if (!$this->vehicleList->contains($vehicleList)) {
            $this->vehicleList->add($vehicleList);
            $vehicleList->setUserId($this);
        }

        return $this;
    }

    public function removeVehicleList(Vehicle $vehicleList): static
    {
        if ($this->vehicleList->removeElement($vehicleList)) {
            // set the owning side to null (unless already changed)
            if ($vehicleList->getUserId() === $this) {
                $vehicleList->setUserId(null);
            }
        }

        return $this;
    }
}
