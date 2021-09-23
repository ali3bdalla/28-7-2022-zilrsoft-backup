<?php

namespace App\Repository\Eloquent;

use App\Dto\InvoiceDto;
use App\Models\Invoice;
use App\Repository\InvoiceRepositoryContract;
use App\ValueObjects\Contract\SearchValueObjectContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
}
