<?php

namespace App\Repository\Eloquent;

use App\Dto\InvoiceDto;
use App\Enums\InvoiceTypeEnum;
use App\Models\Invoice;
use App\Models\Manager;
use App\Models\User;
use App\Repository\InvoiceRepositoryContract;
use App\ValueObjects\Contract\SearchValueObjectContract;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryContract
{


    public function getInvoicesPagination(SearchValueObjectContract $searchValueObjectContract): LengthAwarePaginator
    {
        $queryBuilder = Invoice::with('items.item', "creator", 'user')->withCount(
            ['items AS invoice_cost' => function ($query) {
                $query->select(DB::raw("SUM(cost * qty) as invoice_cost"));
            },
            ]
        );
        $queryBuilder = $searchValueObjectContract->apply($queryBuilder);
        return $queryBuilder->paginate(5);
    }

    public function createInvoice(InvoiceDto $invoiceDto): ?Invoice
    {
        return DB::transaction(function () use ($invoiceDto) {
            $invoice = Invoice::factory()->setDto($invoiceDto)->create();
            $invoice->addItems($invoiceDto->getItems());
            return $invoice;
        }, 3);
    }

    public function getInvoiceFullDetails(Invoice $invoice): Invoice
    {
        return $invoice->load([
            'items.item.serials' => function ($query) use ($invoice) {
                return $query
                    ->where('sale_id', $invoice->id)
                    ->orWhere('return_sale_id', $invoice->id)
                    ->orWhere('return_purchase_id', $invoice->id)
                    ->orWhere('purchase_id', $invoice->id);
            },
            "user",
            "manager"
        ]);
    }

    public function getQuickBooksTodaySales(Manager $manager): Collection
    {
        if (!$manager->organization->has_quickbooks || !$manager->quickBooksToken) return collect([]);
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $manager
        ]);
        $today  = Carbon::now()->format("Y-m-d");
        $receipts = $quickBooksDataService->Query("SELECT TotalAmt FROM SalesReceipt WHERE txnDate='$today'");
        $invoices = $quickBooksDataService->Query("SELECT TotalAmt FROM Invoice WHERE txnDate='$today'");
        return collect(array_merge($receipts ?? [],$invoices ?? []));
    }
}
