<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wallet $wallet = null;

    #[ORM\Column]
    private ?float $walletBalanceBeforeOperation = null;

    #[ORM\Column]
    private ?float $walletBalanceAfterOperation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getWalletBalanceBeforeOperation(): ?float
    {
        return $this->walletBalanceBeforeOperation;
    }

    public function setWalletBalanceBeforeOperation(float $walletBalanceBeforeOperation): self
    {
        $this->walletBalanceBeforeOperation = $walletBalanceBeforeOperation;

        return $this;
    }

    public function getWalletBalanceAfterOperation(): ?float
    {
        return $this->walletBalanceAfterOperation;
    }

    public function setWalletBalanceAfterOperation(float $walletBalanceAfterOperation): self
    {
        $this->walletBalanceAfterOperation = $walletBalanceAfterOperation;

        return $this;
    }
}
