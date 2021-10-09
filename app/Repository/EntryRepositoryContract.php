<?php

namespace App\Repository;

use App\Enums\EntryDto;
use App\Models\Account;
use App\Models\Payment;
use App\Models\ResellerClosingAccount;
use App\Models\TransactionsContainer;

interface EntryRepositoryContract extends BaseRepositoryContract
{
    public function createEntry(EntryDto $entryDto): TransactionsContainer;

    public function registerManagerWalletTransferTransactionEntry(ResellerClosingAccount $pendingTransaction, float $remainingWalletBalance): TransactionsContainer;

    public function registerClientVoucherEntry(Payment $voucher,Account  $targetAccount): TransactionsContainer;

    public function registerVendorVoucherEntry(Payment $voucher,Account  $targetAccount): TransactionsContainer;
}
