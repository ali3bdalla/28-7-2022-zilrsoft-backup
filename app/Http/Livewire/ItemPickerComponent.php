<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;

class ItemPickerComponent extends Component
{
    public ?string $search = null;

    public function getItemsProperty()
    {
        if ($this->search == null) {
            return [];
        }
        $list = Item::search($this->search)->take(5)->get();
        if (count($list) == 1) {
            $this->broadcastPickedItem($list->first());
            return [];
        }
        return $list;
    }


    public function broadcastPickedItem(Item $item)
    {
        $this->emit('itemPicked', $item);
        $this->search = null;
    }


    public function render()
    {
        return view('livewire.item-picker-component');
    }
}
