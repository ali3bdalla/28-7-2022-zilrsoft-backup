<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    //
    /**
     * Handle the organization "created" event.
     *
     * @param  \App\Models\Invoice  $account
     * @return void
     */
    public function created(Invoice $invoice)
    {
        
    }

    /**
     * Handle the organization "updated" event.
     *
     * @param  \App\Models\Invoice  $account
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        
    }

    /**
     * Handle the organization "deleted" event.
     *
     * @param  \App\Models\Invoice  $account
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        
    }

    /**
     * Handle the organization "restored" event.
     *
     * @param  \App\Models\Invoice  $account
     * @return void
     */
    public function restored(Invoice $invoice)
    {
        
    }

    /**
     * Handle the organization "force deleted" event.
     *
     * @param  \App\Models\Invoice  $account
     * @return void
     */
    public function forceDeleted(Invoice $invoice)
    {
       
    }
}
