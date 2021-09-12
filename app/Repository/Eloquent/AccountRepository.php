<?php

namespace App\Repository\Eloquent;

use App\Http\Resources\Entity\TransactionResource;
use App\Models\Account;
use App\Models\Manager;
use App\Models\Transaction;
use App\Repository\AccountRepositoryContract;
use App\ValueObjects\AccountSearchValueObject;
use App\ValueObjects\Contract\SearchValueObjectContract;
use App\ValueObjects\Contract\SortingValueObjectContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AccountRepository extends BaseRepository implements AccountRepositoryContract
{

    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    public function getAccountTransactionsListPagination(Account $account, SearchValueObjectContract $searchValueObjectContract, SortingValueObjectContract $sortingValueObjectContract): AnonymousResourceCollection
    {
        $queryBuilder = Transaction::whereAccountId($account->id)->with("account", 'invoice', 'user', 'item');
        $queryBuilder = $searchValueObjectContract->apply($queryBuilder);
        $queryBuilder = $sortingValueObjectContract->sort($queryBuilder);
        return TransactionResource::collection($queryBuilder->paginate(100));

    }

    public function getAccountChildrenList(Account $account): Collection
    {
        return $account->children()->withCount('children')
            ->orderBy('sorting_number', 'desc')
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function getAccountsList(AccountSearchValueObject $accountSearchValueObject = null): Collection
    {
        $queryBuilder = $accountSearchValueObject->apply($this->model->query());
        return $queryBuilder->withCount('children')->get();
    }

    public function getAccountBalance(Account $account, SearchValueObjectContract $searchValueObjectContract)
    {
        $debitAmount = $searchValueObjectContract->apply(Transaction::whereAccountId($account->id)->whereType('debit')->with("account", 'invoice', 'user', 'item'))->sum('amount');
        $creditAmount = $searchValueObjectContract->apply(Transaction::whereAccountId($account->id)->whereType('credit')->with("account", 'invoice', 'user', 'item'))->sum('amount');
        if ($account->isCredit()) return $creditAmount - $debitAmount;
        return $debitAmount - $creditAmount;
    }

    public function getPaymentMethodsAccountsListToAuthedManager(): Collection
    {
        return Account::whereSlug("temp_reseller_account")->whereIsSystemAccount(true)->get();
    }
}
