<?php

namespace App\Repository\Eloquent;

use App\Enums\AccountingTypeEnum;
use App\Enums\EntryDto;
use App\Enums\TransactionDto;
use App\Enums\VoucherTypeEnum;
use App\Jobs\Accounting\Sale\StoreSaleTransactionsJob;
use App\Models\Account;
use App\Models\Entry;
use App\Models\Invoice;
use App\Models\ResellerClosingAccount;
use App\Models\Voucher;
use App\Repository\AccountRepositoryContract;
use App\Repository\EntryRepositoryContract;
use App\Repository\UserRepositoryContract;
use Illuminate\Support\Facades\DB;

class EntryRepository extends BaseRepository implements EntryRepositoryContract
{
    private AccountRepositoryContract $accountRepositoryContract;
    private UserRepositoryContract $userRepositoryContract;

    public function __construct(AccountRepositoryContract $accountRepositoryContract, UserRepositoryContract $userRepositoryContract)
    {
        $this->accountRepositoryContract = $accountRepositoryContract;
        $this->userRepositoryContract = $userRepositoryContract;
    }

    public function registerManagerWalletTransferTransactionEntry(ResellerClosingAccount $pendingTransaction, float $remainingWalletBalance = 0): Entry
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

    public function createEntry(EntryDto $entryDto): Entry
    {
        return DB::transaction(function () use ($entryDto) {
            $entry = Entry::factory()->setDto($entryDto)->create();
            $entry->addTransactions($entryDto->getTransactions());
            return $entry->load('transactions');
        });
    }

    public function registerClientVoucherEntry(Voucher $voucher, Account $targetAccount): Entry
    {
        $clientAccount = Account::getSystemAccount("clients");
        $transactions = collect();
        $receivedAmountFromTheClient = $voucher->payment_type->equals(VoucherTypeEnum::receipt());
        $transactions->add(new TransactionDto(
            $clientAccount,
            $receivedAmountFromTheClient ? AccountingTypeEnum::credit() : AccountingTypeEnum::debit(),
            $voucher->amount,
            false,
            false,
            null,
            null,
            $voucher->user_id
        ));
        $transactions->add(new TransactionDto(
            $targetAccount,
            $receivedAmountFromTheClient ? AccountingTypeEnum::debit() : AccountingTypeEnum::credit(),
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
        return DB::transaction(function () use ($entryDto, $voucher, $receivedAmountFromTheClient) {
            $entry = $this->createEntry($entryDto);
            $this->userRepositoryContract->updateCustomerBalanceAmount($voucher->user, $voucher->amount, !$receivedAmountFromTheClient);
            return $entry;
        });
    }

    public function registerVendorVoucherEntry(Voucher $voucher, Account $targetAccount): Entry
    {
        $account = Account::getSystemAccount("vendors");
        $transactions = collect();
        $vendorHasBeenPaid = $voucher->payment_type->equals(VoucherTypeEnum::payment());
        $transactions->add(new TransactionDto(
            $account,
            $vendorHasBeenPaid ? AccountingTypeEnum::debit() : AccountingTypeEnum::credit(),
            $voucher->amount,
            false,
            false,
            null,
            null,
            $voucher->user_id
        ));
        $transactions->add(new TransactionDto(
            $targetAccount,
            $vendorHasBeenPaid ? AccountingTypeEnum::credit() : AccountingTypeEnum::debit(),
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
            'vendor receipt voucher',
            null
        );
        return DB::transaction(function () use ($entryDto, $voucher, $vendorHasBeenPaid) {
            $entry = $this->createEntry($entryDto);
            $this->userRepositoryContract->updateVendorBalanceAmount($voucher->user, $voucher->amount, !$vendorHasBeenPaid);
            return $entry;
        });
    }

    public function registerSalesReceipt(Invoice $invoice)
    {
        StoreSaleTransactionsJob::dispatch($invoice);
    }
}
