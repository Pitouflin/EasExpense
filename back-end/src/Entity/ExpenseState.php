<?php

namespace App\Entity;

use App\Repository\ExpenseStateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ExpenseStateRepository::class)]
class ExpenseState
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("expenseReport:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("expenseReport:read")]
    private ?string $value = null;

    /**
     * @var Collection<int, ExpenseReport>
     */
    #[ORM\OneToMany(targetEntity: ExpenseReport::class, mappedBy: 'state')]
    private Collection $ExpenseReportList;

    public function __construct()
    {
        $this->ExpenseReportList = new ArrayCollection();
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, ExpenseReport>
     */
    public function getExpenseReportList(): Collection
    {
        return $this->ExpenseReportList;
    }

    public function addExpenseReportList(ExpenseReport $expenseReportList): static
    {
        if (!$this->ExpenseReportList->contains($expenseReportList)) {
            $this->ExpenseReportList->add($expenseReportList);
            $expenseReportList->setState($this);
        }

        return $this;
    }

    public function removeExpenseReportList(ExpenseReport $expenseReportList): static
    {
        if ($this->ExpenseReportList->removeElement($expenseReportList)) {
            // set the owning side to null (unless already changed)
            if ($expenseReportList->getState() === $this) {
                $expenseReportList->setState(null);
            }
        }

        return $this;
    }
}
