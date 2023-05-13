<?php

namespace App\Command;

use App\Repository\WalletRepository;
use App\Service\Wallet\ExportableCSVInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name       : 'generate-wallet-history-csv',
    description: 'Use this command to generate wallet history operation as CSV file. ',
)]
class GenerateWalletHistoryCommand extends Command
{
    public function __construct(
        private ExportableCSVInterface $exportableCSV,
        private WalletRepository       $walletRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('walletName', null, InputOption::VALUE_REQUIRED, 'Enter the name of Wallet for which you want to generate CSV');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $wallet = $this->walletRepository->findOneBy(['name' => $input->getOption('walletName')]);

        if ($wallet === null) {
            $output->writeln('Cannot find wallet about given name ' . $input->getArgument('walletName'));

            return Command::FAILURE;
        }

        $this->exportableCSV->exportToCSV($wallet);

        $io->success('CSV file was generated.');

        return Command::SUCCESS;
    }
}
