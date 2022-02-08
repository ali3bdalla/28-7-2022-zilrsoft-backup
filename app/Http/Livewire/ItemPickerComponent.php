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
        $list = Item::search($this->search)->where('is_kit', 0)->where('is_need_serial', 0)->take(20)->get();
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
