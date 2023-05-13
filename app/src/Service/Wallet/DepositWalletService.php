<?php

namespace App\Service\Wallet;

class DepositWalletService implements WalletPaymentInterface
{
    public function calculateWalletBalanceAfterOperation(float $currentWalletBalance, float $operationFounds): float
    {
        return $currentWalletBalance + $operationFounds;
    }
}
