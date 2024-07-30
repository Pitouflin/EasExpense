<?php

namespace App\Entity;

use App\Repository\FuelTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FuelTypeRepository::class)]
class FuelType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("fuelType:read", "user:read", "vehicle:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("fuelType:read", "user:read", "vehicle:read")]
    private ?string $name = null;

    /**
     * @var Collection<int, Vehicle>
     */
    #[ORM\OneToMany(targetEntity: Vehicle::class, mappedBy: 'fuelTypeId')]
    #[Groups("fuelType:read", "user:read", "vehicle:read")]
    private Collection $vehicleList;

    public function __construct()
    {
        $this->vehicleList = new ArrayCollection();
    }

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
            $vehicleList->setFuelTypeId($this);
        }

        return $this;
    }

    public function removeVehicleList(Vehicle $vehicleList): static
    {
        if ($this->vehicleList->removeElement($vehicleList)) {
            // set the owning side to null (unless already changed)
            if ($vehicleList->getFuelTypeId() === $this) {
                $vehicleList->setFuelTypeId(null);
            }
        }

        return $this;
    }
}
