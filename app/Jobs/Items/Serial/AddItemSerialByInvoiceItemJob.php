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
    private $isDraft;

    /**
     * Create a new job instance.
     *
     * @param $serials
     * @param InvoiceItems $invoiceItem
     * @param $isDraft
     */
    public function __construct($serials, InvoiceItems $invoiceItem,$isDraft)
    {
        $this->serials = (array) $serials;
        $this->invoiceItem = $invoiceItem;
        $this->isDraft = $isDraft;
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
                'is_draft' => $this->isDraft
            ]);

//            dd($createdSerial)
            if(!$this->isDraft)
            {
                dispatch(new RegisterSerialHistoryJob($createdSerial, 'in_stock',$this->invoiceItem->invoice));

            }
        }

    }
}
