<?php

namespace App\Repository\Eloquent;

use App\Enums\EntryDto;
use App\Enums\VoucherTypeEnum;
use App\Models\Account;
use App\Models\TransactionsContainer;
use App\Repository\AccountsDailyRepositoryContract;
use App\Repository\EntryRepositoryContract;
use App\Repository\VoucherRepositoryContract;
use App\ValueObjects\VoucherSearchValueObject;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccountsDailyRepository extends BaseRepository implements AccountsDailyRepositoryContract
{
    private EntryRepositoryContract $entryRepositoryContract;
    private VoucherRepositoryContract $voucherRepositoryContract;

    public function __construct(EntryRepositoryContract $entryRepositoryContract, VoucherRepositoryContract $voucherRepositoryContract)
    {
        $this->entryRepositoryContract = $entryRepositoryContract;
        $this->voucherRepositoryContract = $voucherRepositoryContract;
    }

    public function closePeriodAccounts(array $banks = [])
    {
        return DB::transaction(function () use ($banks) {
            $transactions = array_merge($this->generateDebitSideTransactionsArray($banks), $this->generateCreditSideTransactionsArray());
            $entryDto = new EntryDto($this->authManager(), new Collection(), false, "close_account");
            $entryDto->setTransactionsFromArray($transactions);
            $entry = $this->entryRepositoryContract->createEntry($entryDto);
            $this->registerDailyAccountsReport($entry, $banks);
            return $entry;
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
            if ((float)$bank['amount']) {
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
        $accountPeriodManualVouchersAmount = $this->voucherRepositoryContract->getAmount(new VoucherSearchValueObject(
            true,
            false,
            $accountId,
            VoucherTypeEnum::receipt(),
            $this->getPeriodStartAt()
        ));
        return $amount - $accountPeriodManualVouchersAmount;
    }

    private function getPeriodStartAt(): Carbon
    {
        return Carbon::parse($this->authManager()->accounts_closed_at ?: $this->authManager()->created_at);
    }

    private function getBanksShortageAmount(array $banks): float
    {
        return $this->getBanksTotalAmount($banks) - $this->getExpectedDailyAmount();
    }

    private function getBanksTotalAmount($bank): float
    {
        return (float)$this->getBanksTransactionsCollection($bank)->sum('amount');
    }

    private function getExpectedDailyAmount(): float
    {
        return ($this->getResellerDailyBankIncomeAmount(false) + $this->authManager()->remaining_accounts_balance) - $this->getResellerDailyBankOutcomeAmount(false);
    }

    public function getResellerDailyBankIncomeAmount(bool $includeManualVouchers = true, ?int $accountId = null): float
    {
        return $this->voucherRepositoryContract->getAmount(new VoucherSearchValueObject(
            true,
            $includeManualVouchers,
            $accountId,
            VoucherTypeEnum::receipt(),
            $this->getPeriodStartAt()
        ));

    }

    public function getResellerDailyBankOutcomeAmount(bool $includeManualVouchers = true, ?int $accountId = null): float
    {
        return $this->voucherRepositoryContract->getAmount(new VoucherSearchValueObject(
            true,
            $includeManualVouchers,
            $accountId,
            VoucherTypeEnum::payment(),
            $this->getPeriodStartAt()
        ));
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
        $this->authManager()->resellerClosingAccounts()->create(
            [
                'organization_id' => $this->authManager()->organization_id,
                'transaction_type' => "close_account",
                'container_id' => $entry->id,
                'from' => $this->authManager()->accounts_closed_at,
                'to' => now(),
                'amount' => $worthyAmount,
                'shortage_amount' => $shortageAmount,
            ]
        );
        $this->authManager()->update([
            'remaining_accounts_balance' => 0,
            'accounts_closed_at' => now(),
        ]);
    }

    private function getTotalDailyWorthyAmount(): float
    {
        return $this->getResellerDailyBankIncomeAmount() - $this->getResellerDailyBankOutcomeAmount();
    }

}
