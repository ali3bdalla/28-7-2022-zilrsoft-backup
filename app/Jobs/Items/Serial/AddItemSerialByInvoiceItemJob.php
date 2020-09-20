<?php

namespace App\Jobs\Items\Serial;

use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddItemSerialByInvoiceItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $serials, $invoiceItem;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($serials, InvoiceItems $invoiceItem)
    {
        $this->serials = (array) $serials;
        $this->invoiceItem = $invoiceItem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ((array)$this->serials as $serial) {
            $createdSerial = $this->invoiceItem->item->serials()->create([
                'purchase_id' => $this->invoiceItem->invoice_id,
                'creator_id' => $this->invoiceItem->creator_id,
                'organization_id' => $this->invoiceItem->organization_id,
                'serial' => $serial,
            ]);
            dispatch(new RegisterSerialHistoryJob($createdSerial, 'in_stock',$this->invoiceItem->invoice));
        }

    }
}
