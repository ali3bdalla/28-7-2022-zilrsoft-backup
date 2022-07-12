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
     * @var bool
     */
    private $isDraft;

    /**
     * Create a new job instance.
     *
     * @param $serials
     * @param InvoiceItems $invoiceItem
     * @param bool $isDraft
     */
    public function __construct($serials, InvoiceItems $invoiceItem, $isDraft = false)
    {
        $this->serials = $serials;
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
        if ($this->invoiceItem->invoice_type == 'return_purchase') {
            foreach ((array)$this->serials as $serial) {
                $dbSerial = $this->invoiceItem->item->serials()->where('serial', $serial)->whereIn('status', ['in_stock', 'return_sale'])->orderBy('id','desc')->first();

                $dbSerial->update([
                    'status' => 'return_purchase',
                    'return_purchase_id' => $this->invoiceItem->invoice_id,
                ]);

                if (!$this->isDraft)
                    dispatch_sync(new RegisterSerialHistoryJob($dbSerial, 'return_purchase', $this->invoiceItem->invoice));
            }
        }



        if ($this->invoiceItem->invoice_type == 'inventory_adjustment') {
            $removedList = [];
            foreach ((array)$this->serials as $serial) {
                $dbSerial = $this->invoiceItem->item->serials()->where('serial', $serial)->orderBy('id','desc')->first();
                if ($dbSerial) {
                    $dbSerial->update([
                        'status' => 'in_stock',
                        'inventory_adjustment_id' => $this->invoiceItem->invoice_id,
                    ]);
                } else {
                    $dbSerial = $this->invoiceItem->item->serials()->create([
                        'serial' => $serial,
                        'inventory_adjustment_id' => $this->invoiceItem->invoice_id,
                        'purchase_id' => $this->invoiceItem->invoice_id,
                        'organization_id' => $this->invoiceItem->organization_id,
                        'creator_id' => $this->invoiceItem->creator_id,
                    ]);
                }

                $removedList[ ] = $dbSerial->id;

                if (!$this->isDraft)
                    dispatch_sync(new RegisterSerialHistoryJob($dbSerial, 'inventory_adjustment', $this->invoiceItem->invoice));
            }

            $removedListItemQuery = $this->invoiceItem->item->serials()->whereNotIn('id',$removedList)->whereIn('status', ['in_stock', 'return_sale']);
            
            $removedListItemQuery->update([
                'inventory_adjustment_id' => $this->invoiceItem->invoice_id,
            ]);
            
            $removedListItemQuery->delete();
        }



        if ($this->invoiceItem->invoice_type == 'warranty_tracing') {
            foreach ((array)$this->serials as $serial) {
                $dbSerial = $this->invoiceItem->item->serials()->where([
                    [
                        'sale_id', $this->invoiceItem->invoice->parent_id,
                    ],
                    [
                        'status', 'sold',
                    ],
                    [
                        'serial', $serial,
                    ],
                ])->first();
                $dbSerial->update([
                    'warranty_tracing_id' => $this->invoiceItem->invoice_id,
                ]);
                if (!$this->isDraft)
                    dispatch_sync(new RegisterSerialHistoryJob($dbSerial, 'warranty_tracing', $this->invoiceItem->invoice));
            }
        }


        if ($this->invoiceItem->invoice_type == 'return_sale') {
            foreach ((array)$this->serials as $serial) {
                $dbSerial = $this->invoiceItem->item->serials()->where([
                    [
                        'sale_id', $this->invoiceItem->invoice->parent_id,
                    ],
                    [
                        'status', 'sold',
                    ],
                    [
                        'serial', $serial,
                    ],
                ])->first();
                $dbSerial->update([
                    'status' => 'return_sale',
                    'return_sale_id' => $this->invoiceItem->invoice_id,
                ]);
                if (!$this->isDraft)
                    dispatch_sync(new RegisterSerialHistoryJob($dbSerial, 'return_sale', $this->invoiceItem->invoice));
            }
        }


        if ($this->invoiceItem->invoice_type == 'sale') {
            foreach ((array)$this->serials as $serial) {
                $dbSerial = $this->invoiceItem->item->serials()->where('serial', $serial)->whereIn('status', ['in_stock', 'return_sale'])->orderBy('id','desc')->first();
                $dbSerial->update([
                    'status' => 'sold',
                    'sale_id' => $this->invoiceItem->invoice_id,
                ]);
                if (!$this->isDraft)
                    dispatch_sync(new RegisterSerialHistoryJob($dbSerial, 'sale', $this->invoiceItem->invoice));
            }
        }
    }
}
