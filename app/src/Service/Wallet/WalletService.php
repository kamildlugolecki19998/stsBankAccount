<?php

namespace App\Service\Wallet;

use App\Entity\Wallet;
use App\Service\Payment\PaymentService;
use Doctrine\ORM\EntityManagerInterface;

class WalletService implements ExportableCSVInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PaymentService         $paymentService
    ) {}

    public function createNewWallet(
        string $walletName,
        float  $startFounds): Wallet
    {
        $wallet = new Wallet();
        $wallet->setName($walletName);
        $wallet->setFounds($startFounds);

        $this->entityManager->persist($wallet);

        return $wallet;
    }

    public function executeWalletPayment(
        Wallet                 $wallet,
        WalletPaymentInterface $walletPaymentInterface,
        string                 $type,
        float                  $operationFounds): Wallet
    {
        $currentWalletBalance = $wallet->getFounds();
        $walletBalanceAfterOperation = $walletPaymentInterface->calculateWalletBalanceAfterOperation($currentWalletBalance, $operationFounds);

        $wallet->setFounds($walletBalanceAfterOperation);

        $payment = $this->paymentService->createNewPayment($currentWalletBalance, $operationFounds, $walletBalanceAfterOperation, $type);

        $wallet->addPayment($payment);


        return $wallet;
    }

    public function exportToCSV(Wallet $wallet)
    {
        $csv =
            [
                'Wallet Name',
                'Operation Type',
                'Operation Value',
                'Operation Date',
                'Wallet Balance Before Operation',
                'Wallet Balance After Operation',
            ];

        $file = fopen($wallet->getName() . '_balance.csv', 'a+');
        fputcsv($file, $csv, ',');

        foreach ($wallet->getPayments() as $payment) {
            $row = [
                $wallet->getName(),
                $payment->getType(),
                $payment->getAmount(),
                $payment->getCreatedAt()->format('Y-m-d H:i:s'),
                $payment->getWalletBalanceBeforeOperation(),
                $payment->getWalletBalanceAfterOperation()
            ];

            fputcsv($file, $row, ',');
        }

        fclose($file);
    }
}
