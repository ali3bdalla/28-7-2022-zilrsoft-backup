<?php

namespace App\Listeners\Item;

use Illuminate\Support\Str;

class UpdateItemSlugListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $item = $event->item;
        if ($item) {
            $item->update([
                'slug' => Str::of($item->getOriginal('name'))->append(" ", $item->id)
            ]);
        }

    }
}
