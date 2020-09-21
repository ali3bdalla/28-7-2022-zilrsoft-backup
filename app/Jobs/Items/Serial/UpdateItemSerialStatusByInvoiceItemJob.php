<?php

namespace App\Jobs\Items\Serial;

use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateItemSerialStatusByInvoiceItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $serials;
    /**
     * @var InvoiceItems
     */
    private $invoiceItem;

    /**
     * Create a new job instance.
     *
     * @param $serials
     * @param InvoiceItems $invoiceItem
     */
    public function __construct($serials, InvoiceItems $invoiceItem)
    {
        $this->serials = $serials;
        $this->invoiceItem = $invoiceItem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->invoiceItem->invoice_type == 'return_purchase') {
            foreach ((array)$this->serials as $serial) {
                $dbSerial = $this->invoiceItem->item->serials()->where('serial', $serial)->whereIn('status', ['in_stock', 'return_sale'])->first();
                $dbSerial->update([
                    'status' => 'return_purchase',
                    'return_purchase_id' => $this->invoiceItem->invoice_id,
                ]);

                dispatch(new RegisterSerialHistoryJob($dbSerial, 'return_purchase', $this->invoiceItem->invoice));
            }
        }
        if ($this->invoiceItem->invoice_type == 'return_sale') {
            foreach ((array)$this->serials as $serial) {
                $dbSerial = $this->invoiceItem->item->serials()->where('serial', $serial)->whereIn('status', ['sold'])->first();
                $dbSerial->update([
                    'status' => 'return_sale',
                    'return_sale_id' => $this->invoiceItem->invoice_id,
                ]);

                dispatch(new RegisterSerialHistoryJob($dbSerial, 'return_sale', $this->invoiceItem->invoice));
            }
        }
        if ($this->invoiceItem->invoice_type == 'sale') {
            foreach ((array)$this->serials as $serial) {
                $dbSerial = $this->invoiceItem->item->serials()->where('serial', $serial)->whereIn('status', ['in_stock', 'return_sale'])->first();
                $dbSerial->update([
                    'status' => 'sold',
                    'sale_id' => $this->invoiceItem->invoice_id,
                ]);

                dispatch(new RegisterSerialHistoryJob($dbSerial, 'sale', $this->invoiceItem->invoice));
            }
        }
    }
}
