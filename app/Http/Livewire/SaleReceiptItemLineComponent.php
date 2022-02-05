<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SaleReceiptItemLineComponent extends Component
{
    public array $invoiceLine;
    public int $index;

    public function mount(array $invoiceLine,int  $index)
    {
        $this->index = $index;
        $this->invoiceLine = $invoiceLine;
    }

    public function updated()
    {
        $invoiceLine = $this->invoiceLine;
        $invoiceLine['total'] = $this->getTotalProperty();
        $invoiceLine['subtotal'] = $this->getSubtotalProperty();
        $invoiceLine['net'] = $this->getNetProperty();
        $invoiceLine['tax'] = $this->getTaxProperty();
        $this->emitUp("invoiceLineUpdatedEvent",[
            'index' => $this->index,
            'invoiceLine' => $invoiceLine
        ]);
    }


    public function getSubtotalProperty()
    {
        if ($this->total == 0) return 0;
        return $this->total - $this->invoiceLine["discount"];
    }

    public function getTaxProperty(): float
    {
        return round($this->subtotal * 15 / 100, 2);
    }

    public function getNetProperty()
    {
        return $this->tax + $this->subtotal;
    }

    public function getTotalProperty()
    {
        if (is_numeric($this->invoiceLine["price"]))
            return $this->invoiceLine["qty"] * $this->invoiceLine["price"];
        return 0;
    }

    public function render()
    {
        return view('livewire.sale-receipt-item-line-component');
    }
}
