<?php

namespace App\Repository\Eloquent;

use App\Models\Account;
use App\Models\ResellerClosingAccount;
use App\Models\Entry;
use App\Repository\AccountRepositoryContract;
use App\Repository\EntryRepositoryContract;
use App\Repository\ManagerDailyWalletRepositoryContract;
use App\Scopes\PendingScope;
use Illuminate\Support\Facades\DB;
use Monolog\Logger;

class ManagerDailyWalletRepository extends BaseRepository implements ManagerDailyWalletRepositoryContract
{
    private EntryRepositoryContract $entryRepositoryContract;
    private AccountRepositoryContract $accountRepositoryContract;

    public function __construct(EntryRepositoryContract $entryRepositoryContract, AccountRepositoryContract $accountRepositoryContract)
    {
        $this->entryRepositoryContract = $entryRepositoryContract;
        $this->accountRepositoryContract = $accountRepositoryContract;
    }

    public function createPendingWalletTransferTransaction(Account $walletAccount, Account $target, float $amount, bool $removeExistingPendingTransaction = false): ResellerClosingAccount
    {
        return DB::transaction(function () use ($walletAccount, $target, $amount, $removeExistingPendingTransaction) {
            if ($removeExistingPendingTransaction) $this->authManager()->resellerClosingAccounts()->withoutGlobalScope(PendingScope::class)->whereIsPending(true)->whereTransactionType("transfer")->forceDelete();

            return $this->authManager()->resellerClosingAccounts()->create(
                [
                    'organization_id' => $this->authManager()->organization_id,
                    'transaction_type' => "transfer",
                    'container_id' => null,
                    'receiver_id' => null,
                    'from' => null,
                    'to' => null,
                    'amount' => $amount,
                    'remaining_accounts_balance' => 0,
                    'is_pending' => true,
                    'from_account_id' => $walletAccount->id,
                    'to_account_id' => $target->id
                ]
            );
        });
    }

    public function hasPendingTransferTransactions(): bool
    {
        return $this->authManager()->resellerClosingAccounts()->withoutGlobalScope(PendingScope::class)->whereTransactionType("transfer")->whereIsPending(true)->count() > 0;
    }

    public function confirmWalletTransferTransaction(ResellerClosingAccount $pendingWalletTransferTransaction): Entry
    {
        return DB::transaction(function () use ($pendingWalletTransferTransaction) {
            $totalSourceWalletBalance = $this->accountRepositoryContract->getAccountBalance($pendingWalletTransferTransaction->fromAccount->fresh());
            $remainingWalletBalance = $totalSourceWalletBalance - $pendingWalletTransferTransaction->amount;
            $entry = $this->entryRepositoryContract->registerManagerWalletTransferTransactionEntry($pendingWalletTransferTransaction, $remainingWalletBalance);
            $pendingWalletTransferTransaction->update([
                'is_pending' => false,
                'container_id' => $entry->id,
                'remaining_accounts_balance' => $remainingWalletBalance
            ]);
            $pendingWalletTransferTransaction->creator()->update([
                'remaining_accounts_balance' => DB::raw("remaining_accounts_balance + $remainingWalletBalance")
            ]);
            return $entry;
        });
    }

    public function cancelWalletTransferTransaction(ResellerClosingAccount $pendingWalletTransferTransaction)
    {
        $pendingWalletTransferTransaction->delete();
    }

    public function getWalletTransferPagination()
    {
        return ResellerClosingAccount::query()
            ->whereCreatorIdAndTransactionTypeOrReceiverId($this->authManager()->id, 'transfer', $this->authManager()->id)
            ->withoutGlobalScope(PendingScope::class)
            ->orderBy('id', 'desc')->paginate(15);
    }
}
