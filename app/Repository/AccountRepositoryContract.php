<?php

namespace App\Repository;

use App\Models\Account;
use App\ValueObjects\AccountSearchValueObject;
use App\ValueObjects\Contract\SearchValueObjectContract;
use App\ValueObjects\Contract\SortingValueObjectContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface AccountRepositoryContract extends BaseRepositoryContract
{
    public function getAccountTransactionsListPagination(Account $account, SearchValueObjectContract $searchValueObjectContract, SortingValueObjectContract $sortingValueObjectContract): AnonymousResourceCollection;

    public function getAccountsList(AccountSearchValueObject $accountSearchValueObject = null): Collection;
}
