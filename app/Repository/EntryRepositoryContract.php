<?php

namespace App\Repository;

use App\Enums\EntryDto;
use App\Models\Account;
use App\Models\Voucher;
use App\Models\ResellerClosingAccount;
use App\Models\Entry;

interface EntryRepositoryContract extends BaseRepositoryContract
{
    public function createEntry(EntryDto $entryDto): Entry;

    public function registerManagerWalletTransferTransactionEntry(ResellerClosingAccount $pendingTransaction, float $remainingWalletBalance): Entry;

    public function registerClientVoucherEntry(Voucher $voucher, Account $targetAccount): Entry;

    public function registerVendorVoucherEntry(Voucher $voucher, Account $targetAccount): Entry;
}
