<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?TransactionType $type = null;

    #[ORM\ManyToOne]
    private ?Budget $budgetCategory = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Month $month = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $from_upload = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?TransactionType
    {
        return $this->type;
    }

    public function setType(?TransactionType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBudgetCategory(): ?Budget
    {
        return $this->budgetCategory;
    }

    public function setBudgetCategory(?Budget $budgetCategory): static
    {
        $this->budgetCategory = $budgetCategory;

        return $this;
    }

    public function getMonth(): ?Month
    {
        return $this->month;
    }

    public function setMonth(?Month $month): static
    {
        $this->month = $month;

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

    public function isFromUpload(): ?bool
    {
        return $this->from_upload;
    }

    public function setFromUpload(?bool $from_upload): static
    {
        $this->from_upload = $from_upload;

        return $this;
    }
}
