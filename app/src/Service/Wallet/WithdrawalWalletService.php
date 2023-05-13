<?php

namespace App\Service\Wallet;

class WithdrawalWalletService implements WalletPaymentInterface
{
    public function calculateWalletBalanceAfterOperation(float $currentWalletBalance, float $operationFounds): float
    {
        return $currentWalletBalance - abs($operationFounds);
    }
}
