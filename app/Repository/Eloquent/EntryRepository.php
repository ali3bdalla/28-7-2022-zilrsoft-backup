<?php

namespace App\Repository\Eloquent;

use App\Enums\AccountingTypeEnum;
use App\Enums\EntryDto;
use App\Enums\TransactionDto;
use App\Models\Account;
use App\Models\Payment;
use App\Models\ResellerClosingAccount;
use App\Models\TransactionsContainer;
use App\Repository\AccountRepositoryContract;
use App\Repository\EntryRepositoryContract;
use App\Repository\UserRepositoryContract;
use Illuminate\Support\Facades\DB;

class EntryRepository extends BaseRepository implements EntryRepositoryContract
{
    private AccountRepositoryContract $accountRepositoryContract;
    private UserRepositoryContract $userRepositoryContract;

    public function __construct(AccountRepositoryContract $accountRepositoryContract,UserRepositoryContract $userRepositoryContract)
    {
        $this->accountRepositoryContract = $accountRepositoryContract;
        $this->userRepositoryContract = $userRepositoryContract;
    }

    public function registerManagerWalletTransferTransactionEntry(ResellerClosingAccount $pendingTransaction, float $remainingWalletBalance = 0): TransactionsContainer
    {
        $totalSourceWalletBalance = $this->accountRepositoryContract->getAccountBalance($pendingTransaction->fromAccount->fresh());
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

    public function registerReceiptVoucherEntry(Payment $voucher, Account $targetAccount): TransactionsContainer
    {
        $clientAccount = Account::getSystemAccount("clients");
        $transactions = collect();
        $transactions->add(new TransactionDto(
            $clientAccount,
            AccountingTypeEnum::credit(),
            $voucher->amount,
            false,
            false,
            null,
            null,
            $voucher->user_id
        ));
        $transactions->add(new TransactionDto(
            $targetAccount,
            AccountingTypeEnum::debit(),
            $voucher->amount,
            false,
            false,
            null,
            null,
            $voucher->user_id
        ));
        $entryDto = new EntryDto(
            $this->authManager(),
            $transactions,
            false,
            'customer receipt voucher',
            null
        );
       return DB::transaction(function() use ($entryDto,$voucher){
           $entry = $this->createEntry($entryDto);
           $this->userRepositoryContract->addCustomerBalanceAmount($voucher->user,$voucher->amount);
           return $entry;
       });
    }
}
