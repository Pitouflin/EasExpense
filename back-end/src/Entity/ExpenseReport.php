<?php

namespace App\Entity;

use App\Repository\ExpenseReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ExpenseReportRepository::class)]
class ExpenseReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("expenseReport:read")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'expenseReportsList')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("expenseReport:read")]
    private ?User $userId = null;

    #[ORM\Column]
    #[Groups("expenseReport:read")]
    private ?float $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }
}
