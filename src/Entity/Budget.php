<?php

namespace App\Entity;

use App\Repository\BudgetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'budgets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Month::class, mappedBy: 'budgets')]
    private Collection $months;

    #[ORM\Column(nullable: true)]
    private ?bool $isSalary = null;

    public function __construct()
    {
        $this->months = new ArrayCollection();
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Month>
     */
    public function getMonths(): Collection
    {
        return $this->months;
    }

    public function addMonth(Month $month): static
    {
        if (!$this->months->contains($month)) {
            $this->months->add($month);
            $month->addBudget($this);
        }

        return $this;
    }

    public function removeMonth(Month $month): static
    {
        if ($this->months->removeElement($month)) {
            $month->removeBudget($this);
        }

        return $this;
    }

    public function isIsSalary(): ?bool
    {
        return $this->isSalary;
    }

    public function setIsSalary(?bool $isSalary): static
    {
        $this->isSalary = $isSalary;

        return $this;
    }
}
