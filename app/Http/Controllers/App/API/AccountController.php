<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\FetchAccountsRequest;
use App\Http\Requests\Account\FetchAccountTransactionRequest;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Models\Account;
use App\Models\Transaction;
use App\Repository\AccountRepositoryContract;
use App\ValueObjects\AccountSearchValueObject;
use App\ValueObjects\GenericSortByValueObject;
use App\ValueObjects\TransactionSearchValueObject;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{

    private AccountRepositoryContract $accountRepositoryContract;

    public function __construct(AccountRepositoryContract $accountRepositoryContract)
    {

        $this->accountRepositoryContract = $accountRepositoryContract;
    }

    public function index(FetchAccountsRequest $request): Collection
    {
        $parentId = $request->getParentId();
        $name = $request->getName();
        return $this->accountRepositoryContract->getAccountsList(new AccountSearchValueObject($parentId, $name));
    }

    public function transactions(Account $account, FetchAccountTransactionRequest $accountTransactionRequest): AnonymousResourceCollection
    {
        $accountTransactionsSearchValueObject = new TransactionSearchValueObject(
            $accountTransactionRequest->getAmountMoney(),
            $accountTransactionRequest->getUserId(),
            $accountTransactionRequest->getInvoiceId(),
            $accountTransactionRequest->getItemId(),
            $accountTransactionRequest->getStartAt(),
            $accountTransactionRequest->getEndAt()
        );
        $sortTransactionValueObject = new GenericSortByValueObject(new Transaction(), $accountTransactionRequest->getSortColumn(), $accountTransactionRequest->getSortDirection());
        return $this->accountRepositoryContract->getAccountTransactionsListPagination($account, $accountTransactionsSearchValueObject, $sortTransactionValueObject);
    }

    public function store(StoreAccountRequest $request)
    {
        return $request->store();
    }


    /**
     * @param Account $account
     *
     * @return void
     * @throws Exception
     */
    public function destroy(Account $account)
    {
        $account->delete();
        if ($account->parent) $account->parent->updateHashMap();
    }


    public function report(Account $account, Request $request): array
    {
        $startDate = Carbon::parse($request->input('startDate'))->toDateString();
        $endDate = Carbon::parse($request->input('endDate'))->toDateString();

        if ($startDate == $endDate) {
            $totalCredit = $account->transactions()->where('type', 'credit')->whereDate('created_at', $startDate)->sum('amount');
            $totalDebit = $account->transactions()->where('type', 'debit')->whereDate('created_at', $startDate)->sum('amount');
        } else {
            $totalCredit = $account->transactions()->where('type', 'credit')->whereBetween(DB::raw("DATE(created_at)"), [$startDate, $endDate])->sum('amount');
            $totalDebit = $account->transactions()->where('type', 'debit')->whereBetween(DB::raw("DATE(created_at)"), [$startDate, $endDate])->sum('amount');

        }
        if ($account->type == 'credit') {
            $balance = $totalCredit - $totalDebit;
        } else {
            $balance = $totalDebit - $totalCredit;
        }


        return [
            'total_credit' => $totalCredit,
            'total_debit' => $totalDebit,
            'amount' => $balance,
        ];
    }
}
