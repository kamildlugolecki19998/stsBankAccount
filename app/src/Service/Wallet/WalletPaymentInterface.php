<?php

namespace App\Service\Wallet;

interface WalletPaymentInterface
{
    public function calculateWalletBalanceAfterOperation(float $currentWalletBalance, float $operationFounds): float;
}
