<?php

namespace App\Http\Livewire;

use App\Dto\InvoiceDto;
use App\Enums\InvoiceTypeEnum;
use App\Jobs\QuickBook\CreateQuickBooksSalesReceiptJob;
use App\Models\Invoice;
use App\Models\User;
use App\Repository\EntryRepositoryContract;
use App\Repository\InvoiceRepositoryContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SaleReceiptForm extends Component
{
    public ?Collection $items;
    public ?int $customerId = null;
    protected $listeners = ["itemsUpdated", "customerIdUpdated"];

    public function mount()
    {
        CreateQuickBooksSalesReceiptJob::dispatch(Invoice::orderBy('id','desc')->first());
        $this->items = new Collection();
    }

    public function itemsUpdated($items)
    {
        $this->items = collect($items);
    }

    public function save(InvoiceRepositoryContract $invoiceRepositoryContract, EntryRepositoryContract $entryRepositoryContract)
    {
        $validated = $this->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer|exists:items,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'required|numeric',
            'items.*.qty' => 'required|numeric|min:1',
            'customerId' => 'required|integer|exists:users,id',
        ]);
        $invoice = $invoiceRepositoryContract->createInvoice(
            new InvoiceDto(
                Auth::user(),
                User::find($validated['customerId']),
                InvoiceTypeEnum::sale(),
                $this->items->map(function ($item) {
                    $item['id'] = $item['item_id'];
                    $item['serials'] = [];
                    $item['quantity'] = $item['qty'];
                    return $item;
                })->toArray()
            )
        );
//        $entryRepositoryContract->registerSalesReceipt($invoice);
        CreateQuickBooksSalesReceiptJob::dispatch($invoice);
        return $this->redirect('/sales/' . $invoice->id);
    }


    public function customerIdUpdated($value)
    {
        $this->customerId = $value;
    }

    public function render()
    {
        return view('livewire.sale-receipt-form');
    }
}
