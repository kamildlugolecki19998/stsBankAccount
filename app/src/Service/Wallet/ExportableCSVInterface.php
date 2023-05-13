<?php

namespace App\Service\Wallet;

use App\Entity\Wallet;

interface ExportableCSVInterface
{
    public function exportToCSV(Wallet $wallet);
}
