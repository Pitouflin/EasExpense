<?php

namespace App\Entity;

use App\Repository\ExpenseReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ExpenseReportRepository::class)]
class ExpenseReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("expenseReport:read", "user:read")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'expenseReportsList')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("expenseReport:read")]
    private ?User $userId = null;

    #[ORM\Column]
    #[Groups("expenseReport:read", "user:read")]
    private ?float $value = null;

    #[ORM\ManyToOne(inversedBy: 'expenseReportList')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("expenseReport:read", "user:read")]
    private ?ExpenseType $expenseType = null;

    #[ORM\Column(length: 255)]
    #[Groups("expenseReport:read", "user:read")]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'expenseReportList')]
    #[Groups("expenseReport:read", "user:read")]
    private ?Vehicle $vehicle = null;

    #[ORM\ManyToOne(inversedBy: 'ExpenseReportList')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("expenseReport:read", "user:read")]
    private ?ExpenseState $state = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("expenseReport:read", "user:read")]
    private ?string $adminComment = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("expenseReport:read", "user:read")]
    private ?\DateTimeInterface $date = null;

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

    public function getExpenseType(): ?ExpenseType
    {
        return $this->expenseType;
    }

    public function setExpenseType(?ExpenseType $expenseType): static
    {
        $this->expenseType = $expenseType;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getState(): ?ExpenseState
    {
        return $this->state;
    }

    public function setState(?ExpenseState $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getAdminComment(): ?string
    {
        return $this->adminComment;
    }

    public function setAdminComment(?string $adminComment): static
    {
        $this->adminComment = $adminComment;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
    
}
