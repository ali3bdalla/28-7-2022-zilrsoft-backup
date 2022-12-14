<?php

namespace App\Repository\Eloquent;

use App\Enums\AccountingTypeEnum;
use App\Http\Resources\Entity\TransactionResource;
use App\Models\Account;
use App\Models\EntryTransaction;
use App\Repository\AccountRepositoryContract;
use App\ValueObjects\AccountSearchValueObject;
use App\ValueObjects\Contract\SearchValueObjectContract;
use App\ValueObjects\Contract\SortingValueObjectContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AccountRepository extends BaseRepository implements AccountRepositoryContract
{
    public function getAccountTransactionsListPagination(Account $account, SearchValueObjectContract $searchValueObjectContract, SortingValueObjectContract $sortingValueObjectContract): AnonymousResourceCollection
    {
        $queryBuilder = EntryTransaction::whereAccountId($account->id)->with("account", 'invoice', 'user', 'item');
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
        $queryBuilder = $accountSearchValueObject->apply(Account::query());
        return $queryBuilder->withCount('children')->get();
    }

    public function getAccountBalance(Account $account, ?SearchValueObjectContract $searchValueObjectContract = null)
    {
        $debitQuery = EntryTransaction::query()->whereAccountIdAndType($account->id, AccountingTypeEnum::debit());
        $creditQuery = EntryTransaction::query()->whereAccountIdAndType($account->id, AccountingTypeEnum::credit());
        if ($searchValueObjectContract instanceof SearchValueObjectContract) {
            $debitQuery = $searchValueObjectContract->apply($debitQuery);
            $creditQuery = $searchValueObjectContract->apply($creditQuery);
        }
        $debitAmount = $debitQuery->sum('amount');
        $creditAmount = $creditQuery->sum('amount');
        if ($account->isCredit()) return $creditAmount - $debitAmount;
        return $debitAmount - $creditAmount;
    }

    public function getPaymentMethodsAccountsListToAuthedManager(): Collection
    {
        return Account::whereSlug("temp_reseller_account")->whereIsSystemAccount(true)->get();
    }
}
