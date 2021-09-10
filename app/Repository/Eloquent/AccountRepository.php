<?php

namespace App\Repository\Eloquent;

use App\Http\Resources\Entity\TransactionResource;
use App\Models\Account;
use App\Models\Transaction;
use App\Repository\AccountRepositoryContract;
use App\ValueObjects\AccountSearchValueObject;
use App\ValueObjects\Contract\SortingValueObjectContract;
use App\ValueObjects\TransactionSearchValueObject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AccountRepository extends BaseRepository implements AccountRepositoryContract
{

    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    public function getAccountTransactionsListPagination(Account $account, TransactionSearchValueObject $transactionSearchValueObject, SortingValueObjectContract $sortingValueObjectContract): AnonymousResourceCollection
    {
        $queryBuilder = Transaction::whereAccountId($account->id)->with("account", 'invoice', 'user', 'item');
        $queryBuilder = $transactionSearchValueObject->applyToQueryBuilder($queryBuilder);
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
        $queryBuilder = $accountSearchValueObject->applyToQueryBuilder($this->model->query());
        return $queryBuilder->withCount('children')->get();
    }
}
