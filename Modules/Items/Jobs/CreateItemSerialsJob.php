<?php

namespace Modules\Items\Jobs;

use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateItemSerialsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var InvoiceItems
     */
    private $invoiceItem;
    /**
     * @var array
     */
    private $serialsArray;

    /**
     * Create a new job instance.
     *
     * @param InvoiceItems $invoiceItem
     * @param array $serialsArray
     */
    public function __construct(InvoiceItems $invoiceItem, $serialsArray = [])
    {
        //
        $this->invoiceItem = $invoiceItem;
        $this->serialsArray = $serialsArray;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->serialsArray as $serial) {

            $dbSerial = $this->invoiceItem->item->serials()->create([
                'organization_id' => auth()->user()->organization_id,
                'creator_id' => auth()->user()->id,
                'purchase_invoice_id' => $this->invoiceItem->invoice->id,
                'is_pending' => $this->invoiceItem->invoice->invoice_type == 'pending_purchase' ? true : false,
                'item_id' => $this->invoiceItem->item->id,
                'serial' => $serial,
                'current_status' => 'available'
            ]);

            if ($this->invoiceItem->invoice->invoice_type != 'pending_purchase') {
                $this->invoiceItem->invoice->serial_history()->create([
                    'event' => $this->invoiceItem->invoice->invoice_type,
                    'organization_id' => auth()->user()->organization_id,
                    'creator_id' => auth()->user()->id,
                    'serial_id' => $dbSerial->id,
                    'user_id' => $this->invoiceItem->user_id
                ]);
            }

        }
    }
}
