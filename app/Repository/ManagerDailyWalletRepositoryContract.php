<?php

namespace App\Repository;

use App\Models\Account;
use App\Models\ResellerClosingAccount;
use App\Models\Entry;

interface ManagerDailyWalletRepositoryContract extends BaseRepositoryContract
{
    public function hasPendingTransferTransactions(): bool;

    public function getWalletTransferPagination();

    public function confirmWalletTransferTransaction(ResellerClosingAccount $pendingWalletTransferTransaction): Entry;

    public function cancelWalletTransferTransaction(ResellerClosingAccount $pendingWalletTransferTransaction);

    public function createPendingWalletTransferTransaction(Account $walletAccount, Account $target, float $amount, bool $removeExistingPendingTransaction = false): ResellerClosingAccount;
}
