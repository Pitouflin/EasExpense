<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'vehicleList')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FuelType $fuelTypeId = null;

    #[ORM\ManyToOne(inversedBy: 'vehicleList')]
    private ?User $userId = null;

    #[ORM\Column]
    private ?int $fiscalHorses = null;

    #[ORM\Column]
    private ?float $ratioPer20000 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getFuelTypeId(): ?FuelType
    {
        return $this->fuelTypeId;
    }

    public function setFuelTypeId(?FuelType $fuelTypeId): static
    {
        $this->fuelTypeId = $fuelTypeId;

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
}
