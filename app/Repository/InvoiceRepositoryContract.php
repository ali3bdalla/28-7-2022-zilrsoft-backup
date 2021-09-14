<?php

namespace App\Repository;

use App\Dto\InvoiceDto;
use App\Enums\InvoiceTypeEnum;
use App\Models\Invoice;
use App\Models\Manager;
use App\Models\User;
use App\ValueObjects\Contract\SearchValueObjectContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InvoiceRepositoryContract extends BaseRepositoryContract
{
    public function getInvoicesPagination(SearchValueObjectContract $searchValueObjectContract): LengthAwarePaginator;

    public function createInvoice(InvoiceDto $invoiceDto): ?Invoice;
}
