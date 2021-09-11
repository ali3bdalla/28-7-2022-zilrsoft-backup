<?php

namespace App\Repository;

use App\ValueObjects\Contract\SearchValueObjectContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
interface InvoiceRepositoryContract extends BaseRepositoryContract
{
    public function getInvoicesPagination(SearchValueObjectContract $searchValueObjectContract): LengthAwarePaginator;
}
