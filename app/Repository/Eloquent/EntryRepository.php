<?php

namespace App\Repository\Eloquent;

use App\Enums\AccountingTypeEnum;
use App\Enums\EntryDto;
use App\Enums\TransactionDto;
use App\Models\Account;
use App\Models\ResellerClosingAccount;
use App\Models\TransactionsContainer;
use App\Repository\AccountRepositoryContract;
use App\Repository\EntryRepositoryContract;
use Illuminate\Support\Facades\DB;

class EntryRepository extends BaseRepository implements EntryRepositoryContract
{
    private AccountRepositoryContract $accountRepositoryContract;

    public function __construct(AccountRepositoryContract $accountRepositoryContract)
    {
        $this->accountRepositoryContract = $accountRepositoryContract;
    }

    public function registerManagerWalletTransferTransactionEntry(ResellerClosingAccount $pendingTransaction): TransactionsContainer
    {
        $totalSourceWalletBalance = $this->accountRepositoryContract->getAccountBalance($pendingTransaction->fromAccount->fresh());
        $remainingWalletBalance = $totalSourceWalletBalance - $pendingTransaction->amount;
        $tempResellerAccount = Account::getSystemAccount("temp_reseller_account");
        $transactions = collect();
        $transactions->add(new TransactionDto(
            $pendingTransaction->fromAccount,
            AccountingTypeEnum::credit(),
            abs($totalSourceWalletBalance),
        ));
        $transactions->add(new TransactionDto(
            $pendingTransaction->toAccount,
            AccountingTypeEnum::debit(),
            $pendingTransaction->amount,
        ));
        if ($remainingWalletBalance > 0) {
            $transactions->add(new TransactionDto(
                $tempResellerAccount,
                AccountingTypeEnum::debit(),
                $remainingWalletBalance,
            ));
        } else {
            $transactions->add(new TransactionDto(
                $tempResellerAccount,
                AccountingTypeEnum::credit(),
                abs($remainingWalletBalance),
            ));
        }
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
