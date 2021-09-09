<?php

namespace App\Repository\Eloquent;

use App\Enums\VoucherTypeEnum;
use App\Models\Account;
use App\Models\Manager;
use App\Models\ResellerClosingAccount;
use App\Models\TransactionsContainer;
use App\Repository\AccountsDailyRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountsDailyRepository extends BaseRepository implements AccountsDailyRepositoryContract
{

    public function __construct(ResellerClosingAccount $model)
    {
        parent::__construct($model);
    }

    public function createDailyCloseAccountAggregate(array $banks = [])
    {
        return DB::transaction(function () use ($banks) {
            $transactions = array_merge($this->generateDebitSideTransactionsArray($banks), $this->generateCreditSideTransactionsArray());
            $container = TransactionsContainer::createEntry(['description' => 'close_account'], $transactions);
            $this->registerDailyAccountsReport($container, $banks);
            return $container;
        });
    }

    private function generateDebitSideTransactionsArray($banks): array
    {
        $debitTransactions = $this->getBanksTransactionsCollection($banks)->toArray();
        $shortShortageAmount = $this->getBanksShortageAmount($banks);
        if ($shortShortageAmount != 0)
            $debitTransactions[] = $this->generateShortageTransaction($shortShortageAmount);
        return $debitTransactions;
    }

    private function getBanksTransactionsCollection(array $banks): Collection
    {
        $transactions = [];
        foreach ($banks as $bank) {
            if ($bank['amount'] > 0) {
                $transactions[] = [
                    'account_id' => $bank['id'],
                    'description' => 'close_account',
                    'amount' => $this->getGatewayExpectedDailyAmount((float)$bank['amount'], $bank['id']),
                    'type' => 'debit',
                ];
            }
        }
        return collect($transactions);
    }

    private function getGatewayExpectedDailyAmount(float $amount, $accountId): float
    {
        return $amount - ($this->getResellerDailyBankIncomeAmount(false, $accountId) - $this->getResellerDailyBankOutcomeAmount(false, $accountId));
    }

    public function getResellerDailyBankIncomeAmount($excludeVouchers = false, $accountId = null): float
    {
        return $this->getResellerDailyAmount(VoucherTypeEnum::receipt(), $excludeVouchers, $accountId);
    }

    public function getResellerDailyAmount(VoucherTypeEnum $voucherTypeEnum, $excludeVouchers = false, $accountId = null): float
    {
        $startAt = $this->user()->accounts_closed_at ?: $this->user()->created_at;
        $query = $this->user()->payments()->where([
            ['created_at', '>=', Carbon::parse($startAt)],
            ['payment_type', $voucherTypeEnum->value]
        ]);
        if ($excludeVouchers) $query = $query->whereNotNull('invoice_id');
        if ($accountId) $query = $query->where('account_id', $accountId);
        return (float)$query->sum('amount');
    }

    private function user(): Manager
    {
        return Auth::user();
    }

    public function getResellerDailyBankOutcomeAmount($excludeVouchers = false, $accountId = null): float
    {
        return $this->getResellerDailyAmount(VoucherTypeEnum::payment(), $excludeVouchers, $accountId);
    }

    private function getBanksShortageAmount($bank): float
    {
        return $this->getBanksTotalAmount($bank) - $this->getExpectedDailyAmount();
    }

    private function getBanksTotalAmount($bank): float
    {
        return (float)$this->getBanksTransactionsCollection($bank)->sum('amount');
    }

    private function getExpectedDailyAmount(): float
    {
        return $this->getResellerDailyBankIncomeAmount(true) - $this->getResellerDailyBankOutcomeAmount(true);
    }

    private function generateShortageTransaction(float $shortShortageAmount): array
    {
        $transactionData = [];
        $shiftsShortageAccount = Account::getSystemAccount("shifts_shortage");
        $transactionData['account_id'] = $shiftsShortageAccount->id;
        $transactionData['type'] = $shortShortageAmount < 0 ? "debit" : "credit";
        $transactionData['amount'] = abs($shortShortageAmount);
        return $transactionData;
    }

    private function generateCreditSideTransactionsArray(): array
    {
        $tempResellerAccount = Account::getSystemAccount("temp_reseller_account");
        $transactionData['account_id'] = $tempResellerAccount->id;
        $transactionData['amount'] = $this->getExpectedDailyAmount();
        $transactionData['type'] = 'credit';
        return [$transactionData];
    }

    private function registerDailyAccountsReport(TransactionsContainer $entry, $banks)
    {
        $actualAmount = collect($banks)->sum('amount');
        $worthyAmount = $this->getTotalDailyWorthyAmount();
        $shortageAmount = $actualAmount - $worthyAmount;
        $this->user()->resellerClosingAccounts()->create(
            [
                'organization_id' => $this->user()->organization_id,
                'transaction_type' => "close_account",
                'container_id' => $entry->id,
                'from' => $this->user()->accounts_closed_at,
                'to' => now(),
                'amount' => $worthyAmount,
                'shortage_amount' => $shortageAmount,
            ]
        );
        $this->user()->update([
            'remaining_accounts_balance' => 0,
            'accounts_closed_at' => now(),
        ]);
    }

    private function getTotalDailyWorthyAmount(): float
    {
        return $this->getResellerDailyBankIncomeAmount() - $this->getResellerDailyBankOutcomeAmount();
    }

}
