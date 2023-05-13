<?php

namespace App\Service\Payment;

use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;

class PaymentService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function createNewPayment(float $currentWalletBalance, float $operationFounds, string $walletBalanceAfterOperation, string $paymentType): Payment
    {
        $payment = new Payment();
        $payment->setAmount($operationFounds);
        $payment->setWalletBalanceBeforeOperation($currentWalletBalance);
        $payment->setWalletBalanceAfterOperation($walletBalanceAfterOperation);
        $payment->setType($paymentType);
        $payment->setCreatedAt(new \DateTime());

        $this->entityManager->persist($payment);

        return $payment;
    }
}
