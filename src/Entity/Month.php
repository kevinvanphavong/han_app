<?php

namespace App\Entity;

use App\Repository\MonthRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonthRepository::class)]
class Month
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $balance = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalAmountSpent = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalAmountEarned = null;

    #[ORM\ManyToOne(inversedBy: 'months')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(?float $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getTotalAmountSpent(): ?float
    {
        return $this->totalAmountSpent;
    }

    public function setTotalAmountSpent(?float $totalAmountSpent): static
    {
        $this->totalAmountSpent = $totalAmountSpent;

        return $this;
    }

    public function getTotalAmountEarned(): ?float
    {
        return $this->totalAmountEarned;
    }

    public function setTotalAmountEarned(?float $totalAmountEarned): static
    {
        $this->totalAmountEarned = $totalAmountEarned;

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
