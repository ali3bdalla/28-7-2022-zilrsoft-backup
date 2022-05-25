<?php

namespace App\Repository;

use App\Dto\InvoiceDto;
use App\Enums\AccountingTypeEnum;
use App\Models\Invoice;
use App\Models\Manager;
use App\Models\User;
use App\ValueObjects\Contract\SearchValueObjectContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface InvoiceRepositoryContract extends BaseRepositoryContract
{
    public function getInvoicesPagination(SearchValueObjectContract $searchValueObjectContract): LengthAwarePaginator;
    public function getQuickBooksTodaySales(Manager $manager): Collection;
    public function createInvoice(InvoiceDto $invoiceDto): ?Invoice;
    public function getInvoiceFullDetails(Invoice $invoice);
}
