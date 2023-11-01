<?php

namespace App\Entity;

use App\Repository\TransactionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionTypeRepository::class)]
class TransactionType
{
    public const TYPE_SPENT_NAME = 'Spent';
    public const TYPE_SPENT_NUMBER = 0;
    public const TYPE_COLLECTED = 'Collected';
    public const TYPE_COLLECTED_NUMBER = 1;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $associatedNumber = null;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
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

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setType($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getType() === $this) {
                $transaction->setType(null);
            }
        }

        return $this;
    }

    public function getAssociatedNumber(): ?int
    {
        return $this->associatedNumber;
    }

    public function setAssociatedNumber(int $associatedNumber): static
    {
        $this->associatedNumber = $associatedNumber;

        return $this;
    }
}
