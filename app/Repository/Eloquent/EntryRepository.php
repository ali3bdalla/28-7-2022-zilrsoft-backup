<?php

namespace App\Repository\Eloquent;

use App\Enums\AccountingTypeEnum;
use App\Enums\EntryDto;
use App\Enums\TransactionDto;
use App\Models\ResellerClosingAccount;
use App\Models\TransactionsContainer;
use App\Repository\EntryRepositoryContract;
use Illuminate\Support\Facades\DB;

class EntryRepository extends BaseRepository implements EntryRepositoryContract
{

    public function registerManagerWalletTransferTransactionEntry(ResellerClosingAccount $pendingTransaction): TransactionsContainer
    {
        $transactions = collect();
        $transactions->add(new TransactionDto(
            $pendingTransaction->fromAccount,
            AccountingTypeEnum::credit(),
            $pendingTransaction->amount,
        ));
        $transactions->add(new TransactionDto(
            $pendingTransaction->toAccount,
            AccountingTypeEnum::debit(),
            $pendingTransaction->amount,
        ));
        $entryDto = new EntryDto(
            $pendingTransaction->creator,
            $transactions,
            false,
            'wallet transfer transaction'
        );
        return $this->createEntry($entryDto);
    }

    public function createEntry(EntryDto $entryDto): TransactionsContainer
    {
        return DB::transaction(function () use ($entryDto) {
            $entry = TransactionsContainer::factory()->setDto($entryDto)->create();
            $entry->addTransactions($entryDto->getTransactions());
            return $entry->load('transactions');
        });
    }
}
