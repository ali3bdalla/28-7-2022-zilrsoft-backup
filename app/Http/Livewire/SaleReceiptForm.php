<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class SaleReceiptForm extends Component
{
    public Collection $items;
    public ?int $customerId = null;
    protected $listeners = ["itemsUpdated", "customerIdUpdated"];

    public function mount()
    {
        $this->items = collect();
    }
    public function itemsUpdated($items)
    {
        $this->items = collect($items);
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
