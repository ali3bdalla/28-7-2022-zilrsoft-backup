<?php

namespace App\Listeners\Item;

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
                'en_slug' => str_replace(' ', '-', $item->getOriginal('name') . '-' . ' ' . $item->id),
                'ar_slug' => str_replace(' ', '-', $item->getOriginal('ar_name') . '-' . ' ' . $item->id),
            ]);
        }

    }
}
