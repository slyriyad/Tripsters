<?php

namespace App\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExpenseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
class Expense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;


    #[ORM\ManyToOne(inversedBy: 'expenses')]
    private ?Trip $trip = null;

    #[ORM\ManyToOne(inversedBy: 'expenses')]
    private ?CategoryExpense $categoryExpense = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'paidExpenses')]
    private ?User $paidBy = null;


    /**
     * @var Collection<int, ExpenseSplit>
     */
    #[ORM\OneToMany(mappedBy: 'expense', targetEntity: ExpenseSplit::class, cascade: ['persist', 'remove'])]
    private Collection $expenseSplits;


    public function __construct()
    {
        $this->expenseSplits = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): static
    {
        $this->trip = $trip;

        return $this;
    }

    public function getCategoryExpense(): ?CategoryExpense
    {
        return $this->categoryExpense;
    }

    public function setCategoryExpense(?CategoryExpense $categoryExpense): static
    {
        $this->categoryExpense = $categoryExpense;

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

    /**
     * @return Collection<int, ExpenseSplit>
     */
    public function getExpenseSplits(): Collection
    {
        return $this->expenseSplits;
    }

    public function addExpenseSplit(ExpenseSplit $expenseSplit): static
    {
        if (!$this->expenseSplits->contains($expenseSplit)) {
            $this->expenseSplits->add($expenseSplit);
            $expenseSplit->setExpense($this);
        }

        return $this;
    }

    public function removeExpenseSplit(ExpenseSplit $expenseSplit): static
    {
        if ($this->expenseSplits->removeElement($expenseSplit)) {
            // set the owning side to null (unless already changed)
            if ($expenseSplit->getExpense() === $this) {
                $expenseSplit->setExpense(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of paidBy
     */ 
    public function getPaidBy()
    {
        return $this->paidBy;
    }

    /**
     * Set the value of paidBy
     *
     * @return  self
     */ 
    public function setPaidBy($paidBy)
    {
        $this->paidBy = $paidBy;

        return $this;
    }
}