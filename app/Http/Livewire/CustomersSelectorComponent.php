<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class CustomersSelectorComponent extends Component
{
    public ?int $selectedCustomer = null;
    public function getCustomersProperty() {
        return User::where('is_client',true)->get();
    }


    public function updatedSelectedCustomer($value)
    {
        $this->emitUp("customerIdUpdated",$value);
    }
    public function render()
    {
        return view('livewire.customers-selector-component');
    }
}
