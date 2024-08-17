<?php

namespace App\Entity;

use App\Repository\ExpenseTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ExpenseTypeRepository::class)]
class ExpenseType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("expenseType:read" , "expenseReport:read" ,  "user:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("expenseType:read" , "expenseReport:read" , "user:read")]
    private ?string $name = null;
    /**
     * @var Collection<int, ExpenseReport>
     */
    #[ORM\OneToMany(targetEntity: ExpenseReport::class, mappedBy: 'expenseType')]
    private Collection $expenseReportList;

    public function __construct()
    {
        $this->expenseReportList = new ArrayCollection();
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
            $expenseReportList->setExpenseType($this);
        }

        return $this;
    }

    public function removeExpenseReportList(ExpenseReport $expenseReportList): static
    {
        if ($this->expenseReportList->removeElement($expenseReportList)) {
            // set the owning side to null (unless already changed)
            if ($expenseReportList->getExpenseType() === $this) {
                $expenseReportList->setExpenseType(null);
            }
        }

        return $this;
    }
}
