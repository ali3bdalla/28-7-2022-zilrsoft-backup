<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Daily\StoreResellerAccountTransactionRequest;
use App\Http\Requests\Daily\StoreResellerClosingAccountsRequest;
use App\Models\ResellerClosingAccount;
use App\Notifications\Daily\IssuedDailyTransferNotification;
use App\Notifications\Daily\TransferWalletTransactionCanceledNotification;
use App\Notifications\Daily\TransferWalletTransactionConfirmedNotification;
use App\Repository\AccountsDailyRepositoryContract;
use App\Repository\ManagerDailyWalletRepositoryContract;
use App\Scopes\PendingScope;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Throwable;

class DailyController extends Controller
{

    private AccountsDailyRepositoryContract $accountsDailyRepositoryContract;
    private ManagerDailyWalletRepositoryContract $managerDailyWalletRepositoryContract;

    public function __construct(AccountsDailyRepositoryContract $accountsDailyRepositoryContract, ManagerDailyWalletRepositoryContract $managerDailyWalletRepositoryContract)
    {
        $this->accountsDailyRepositoryContract = $accountsDailyRepositoryContract;
        $this->managerDailyWalletRepositoryContract = $managerDailyWalletRepositoryContract;
    }

    /**
     * @throws Exception
     */
    public function storeResellerClosingAccount(StoreResellerClosingAccountsRequest $request)
    {
        return $this->accountsDailyRepositoryContract->closePeriodAccounts($request->getBanks());
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function issueWalletTransfer(StoreResellerAccountTransactionRequest $request)
    {
        $request->ensureNoOldPendingTransferExists($this->managerDailyWalletRepositoryContract);
        $amount = $request->getAmount();
        $walletAccount = $request->getWalletAccount();
        $targetAccount = $request->getTargetAccount();
        $removeExistingPendingTransaction = $request->getRemoveExistingPendingTransactions();
        $pendingWalletTransferTransaction = $this->managerDailyWalletRepositoryContract->createPendingWalletTransferTransaction($walletAccount, $targetAccount, $amount, $removeExistingPendingTransaction);
        Notification::send($targetAccount->managerGateways()->get(), new IssuedDailyTransferNotification($pendingWalletTransferTransaction));
        return response($pendingWalletTransferTransaction);
    }


    public function confirmWalletTransfer($transaction)
    {
        $transaction = ResellerClosingAccount::query()
            ->whereIsPendingAndId(true, $transaction)
            ->withoutGlobalScope(PendingScope::class)->firstOrFail();

        if (!in_array(Auth::id(), $transaction->toAccount->managerGateways()->pluck('managers.id')->toArray())) {
            abort(404);
        }
        $this->managerDailyWalletRepositoryContract->confirmWalletTransferTransaction($transaction);
        $transaction->creator->notify(new TransferWalletTransactionConfirmedNotification($transaction));
    }


    public function cancelWalletTransferTransaction($transaction)
    {
        $transaction = ResellerClosingAccount::query()
            ->whereIsPendingAndId(true, $transaction)
            ->withoutGlobalScope(PendingScope::class)->firstOrFail();

        $allowedManagers = $transaction->toAccount->managerGateways()->pluck('managers.id')->toArray();
        $allowedManagers[] = $transaction->creator_id;
        if (!in_array(Auth::id(), $allowedManagers)) {
            abort(404);
        }
        $this->managerDailyWalletRepositoryContract->cancelWalletTransferTransaction($transaction);
        $transaction->creator->notify(new TransferWalletTransactionCanceledNotification($transaction));

    }

}
