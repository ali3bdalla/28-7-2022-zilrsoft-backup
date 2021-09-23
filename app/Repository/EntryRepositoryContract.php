<?php

namespace App\Repository;

use App\Enums\EntryDto;
use App\Models\ResellerClosingAccount;
use App\Models\TransactionsContainer;

interface EntryRepositoryContract extends BaseRepositoryContract
{
    public function createEntry(EntryDto $entryDto): TransactionsContainer;

    public function registerManagerWalletTransferTransactionEntry(ResellerClosingAccount $pendingTransaction,float $remainingWalletBalance): TransactionsContainer;
}
