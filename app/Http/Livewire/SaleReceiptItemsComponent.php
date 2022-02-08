<?php

namespace App\Http\Livewire;

use App\Models\InvoiceItems;
use App\Models\Item;
use Livewire\Component;

class SaleReceiptItemsComponent extends Component
{
    public array $items = [];
    protected $listeners = ["itemPicked", "invoiceLineUpdatedEvent"];

    public function invoiceLineUpdatedEvent(array $data)
    {
        $this->items[$data['index']] = $data['invoiceLine'];
        $this->broadcast();
    }

    public function itemPicked(Item $item)
    {
        if (collect($this->items)->where('item_id', $item->id)->isEmpty()) {
            $invoiceItem = new InvoiceItems();
            $invoiceItem->price = $item->price;
            $invoiceItem->item_id = $item->id;
            $invoiceItem->qty = 1;
            $invoiceItem->discount = 0;
            $invoiceItem->item = $item;
            $this->items[] = $invoiceItem->toArray();
            $this->broadcast();
        }

    }

    public function broadcast()
    {
        $this->emitUp("itemsUpdated",$this->items);
    }
    public function removeItem(InvoiceItems $invoiceItem)
    {

    }

    public function render()
    {
        return view('livewire.sale-receipt-items-component');
    }
}
