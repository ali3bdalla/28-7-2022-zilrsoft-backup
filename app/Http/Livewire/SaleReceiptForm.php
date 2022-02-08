<?php

namespace App\Http\Livewire;

use App\Dto\InvoiceDto;
use App\Enums\InvoiceTypeEnum;
use App\Models\User;
use App\Repository\AccountRepositoryContract;
use App\Repository\EntryRepositoryContract;
use App\Repository\InvoiceRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SaleReceiptForm extends Component
{
    public ?array $items = null;
    public ?int $customerId = null;
    protected $listeners = ["itemsUpdated", "customerIdUpdated"];


    public function itemsUpdated($items)
    {
        $this->items = $items;
    }

    public function save(Request $request, InvoiceRepositoryContract $invoiceRepositoryContract,EntryRepositoryContract $entryRepositoryContract)
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
                collect($validated['items'])->map(function ($item) {
                    $item['id'] = $item['item_id'];
                    $item['serials'] = [];
                    $item['quantity'] = $item['qty'];
                    return $item;
                })->toArray()
            )
        );
//        $entryRepositoryContract->
//        dd($invoice);
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
