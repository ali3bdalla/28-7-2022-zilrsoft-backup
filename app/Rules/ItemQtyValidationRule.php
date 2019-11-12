<?php

namespace App\Rules;

use App\Item;
use Illuminate\Contracts\Validation\Rule;

class ItemQtyValidationRule implements Rule
{
    public $items;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($items)
    {
        $this->items = $items;
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $index = explode('.', $attribute)[1];
        $job_item = $this->items[0];
        $real_item = Item::find($job_item['id']);



//        if($real_item->available())

        return  $job_item['qty'] <= $real_item->available_qty;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'this item has no enough booked qty'  ;
    }
}
