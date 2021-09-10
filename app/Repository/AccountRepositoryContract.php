<?php

namespace App\Repository;

use App\Models\Account;
use App\ValueObjects\AccountSearchValueObject;
use App\ValueObjects\Contract\SortingValueObjectContract;
use App\ValueObjects\TransactionSearchValueObject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface AccountRepositoryContract extends BaseRepositoryContract
{
    public function getAccountTransactionsListPagination(Account $account, TransactionSearchValueObject $transactionSearchValueObject, SortingValueObjectContract $sortingValueObjectContract): AnonymousResourceCollection;

    public function getAccountsList(AccountSearchValueObject $accountSearchValueObject = null): Collection;
}
