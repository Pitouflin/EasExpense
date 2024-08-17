<?php

// src/Entity/Vehicle.php
namespace App\Entity;

use App\Repository\VehicleRepository ;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["vehicle:read", "user:read", "expenseReport:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["vehicle:read", "user:read", "expenseReport:read"])]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'vehicleList')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["vehicle:read"])]
    private ?User $userId = null;

    #[ORM\Column]
    #[Groups(["vehicle:read", "user:read"])]
    private ?int $fiscalHorses = null;

    #[ORM\Column]
    #[Groups(["vehicle:read", "user:read"])]
    private ?float $ratioPer20000 = null;

    #[ORM\ManyToOne(inversedBy: 'vehiclesList')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["vehicle:read", "user:read"])]
    private ?FuelType $fuelType = null;

    /**
     * @var Collection<int, ExpenseReport>
     */
    #[ORM\OneToMany(targetEntity: ExpenseReport::class, mappedBy: 'vehicle')]
    private Collection $expenseReportList;

    public function __construct()
    {
        $this->expenseReportList = new ArrayCollection();
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

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function getFiscalHorses(): ?int
    {
        return $this->fiscalHorses;
    }

    public function setFiscalHorses(int $fiscalHorses): static
    {
        $this->fiscalHorses = $fiscalHorses;
        return $this;
    }

    public function getRatioPer20000(): ?float
    {
        return $this->ratioPer20000;
    }

    public function setRatioPer20000(float $ratioPer20000): static
    {
        $this->ratioPer20000 = $ratioPer20000;
        return $this;
    }

    public function getFuelType(): ?FuelType
    {
        return $this->fuelType;
    }

    public function setFuelType(?FuelType $fuelType): static
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    /**
     * @return Collection<int, ExpenseReport>
     */
    public function getExpenseReportList(): Collection
    {
        return $this->expenseReportList;
    }

    public function addExpenseReportList(ExpenseReport $expenseReportList): static
    {
        if (!$this->expenseReportList->contains($expenseReportList)) {
            $this->expenseReportList->add($expenseReportList);
            $expenseReportList->setVehicle($this);
        }

        return $this;
    }

    public function removeExpenseReportList(ExpenseReport $expenseReportList): static
    {
        if ($this->expenseReportList->removeElement($expenseReportList)) {
            // set the owning side to null (unless already changed)
            if ($expenseReportList->getVehicle() === $this) {
                $expenseReportList->setVehicle(null);
            }
        }

        return $this;
    }
}
