<?php

namespace App\Jobs\Items\Kit;

use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateKitByInvoiceItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var InvoiceItems
     */
    private $invoiceItem;

    /**
     * Create a new job instance.
     *
     * @param InvoiceItems $invoiceItem
     */
    public function __construct(InvoiceItems $invoiceItem)
    {
        //
        $this->invoiceItem = $invoiceItem->fresh();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $items = $this->invoiceItem->invoice->items()->where([['belong_to_kit',true],['parent_kit_id',$this->invoiceItem->id]])->get();


//        dd($items)
        $result['total'] = 0;
        $result['subtotal'] = 0;
        $result['tax'] = 0;
        $result['discount'] = 0;
        $result['net'] = 0;
        foreach ($items as $item){
            $result['total'] = (float)$result['total'] + (float)$item['total'];
            $result['subtotal'] = (float) $result['subtotal'] + (float)$item['subtotal'];
            $result['tax'] = (float)$result['tax'] + (float)$item['tax'];
            $result['discount'] =(float) $result['discount'] + (float)$item['discount'];
            $result['net'] = (float) $result['net'] + (float)$item['net'];
        }

        $this->invoiceItem->update($result);
    }
}
