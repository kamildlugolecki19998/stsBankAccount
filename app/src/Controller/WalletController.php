<?php

namespace App\Controller;

use App\Const\PaymentType;
use App\Entity\Wallet;
use App\Service\Wallet\DepositWalletService;
use App\Service\Wallet\WalletService;
use App\Service\Wallet\WithdrawalWalletService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class WalletController extends AbstractController
{
    #[
        Rest\Post('/create', name: 'create_new_wallet'),
        Rest\RequestParam(name: 'walletName'),
        Rest\RequestParam(name: 'startFounds')
    ]
    public function createWalletAction(
        EntityManagerInterface $entityManager,
        ParamFetcherInterface  $paramFetcher,
        ValidatorInterface     $validator,
        WalletService          $walletService
    ): JsonResponse
    {
        $wallet = $walletService->createNewWallet($paramFetcher->get('walletName'), (float)$paramFetcher->get('startFounds'));
        $errors = $validator->validate($wallet, groups: ['create']);

        if ($errors->count() > 0) {
            return new JsonResponse((string)$errors, Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse('New wallet created successfully', Response::HTTP_CREATED);
    }

    #[
        Rest\Put('/deposit/{wallet}', name: 'deposit_founds'),
        Rest\RequestParam(name: 'operationFounds')
    ]
    public function depositAction(
        DepositWalletService   $depositWalletService,
        EntityManagerInterface $entityManager,
        ParamFetcherInterface  $paramFetcher,
        ValidatorInterface     $validator,
        Wallet                 $wallet,
        WalletService          $walletService
    ): JsonResponse
    {
        $wallet = $walletService->executeWalletPayment($wallet, $depositWalletService, PaymentType::DEPOSIT, (float)$paramFetcher->get('operationFounds'));
        $errors = $validator->validate($wallet, groups: 'payment');

        if ($errors->count() > 0) {
            return new JsonResponse((string)$errors, Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse(['Updated Wallet' => $wallet->getName()], Response::HTTP_OK);
    }

    #[
        Rest\Put('/withdrawal/{wallet}', name: 'withdrawal'),
        Rest\RequestParam(name: 'operationFounds')
    ]
    public function withdrawalAction(
        EntityManagerInterface  $entityManager,
        ParamFetcherInterface   $paramFetcher,
        ValidatorInterface      $validator,
        Wallet                  $wallet,
        WalletService           $walletService,
        WithdrawalWalletService $withdrawalWalletService
    ): JsonResponse
    {

        $wallet = $walletService->executeWalletPayment($wallet, $withdrawalWalletService, PaymentType::WITHDRAWAL, $paramFetcher->get('operationFounds'));
        $errors = $validator->validate($wallet, groups: 'payment');

        if ($errors->count() > 0) {
            return new JsonResponse((string)$errors, Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse($wallet, Response::HTTP_OK);
    }

    #[Rest\Get('/check/balance/{wallet}', name: 'withdrawal_founds')]
    public function checkWalletBalanceAction(Wallet $wallet): JsonResponse
    {
        return new JsonResponse(['Wallet Balance' => $wallet->getFounds()], Response::HTTP_OK);
    }
}
