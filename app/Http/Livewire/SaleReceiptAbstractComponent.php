<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class SaleReceiptAbstractComponent extends Component
{
    public array $items;

    public function hydrate($items)
    {
        dd($items);
    }
    public function itemCollection(): Collection
    {
       return  new Collection($this->items);
    }
    public function getTotalProperty(): string
    {
        return showMoney($this->itemCollection()->sum('total'));
    }

    public function getSubtotalProperty(): string
    {
        return showMoney($this->itemCollection()->sum('subtotal'));
    }

    public function getTaxProperty(): string
    {
        return showMoney($this->itemCollection()->sum('tax'));
    }


    public function getNetProperty(): string
    {
        return showMoney($this->itemCollection()->sum('net'));
    }

    public function render()
    {
        return view('livewire.sale-receipt-abstract-component');
    }
}
