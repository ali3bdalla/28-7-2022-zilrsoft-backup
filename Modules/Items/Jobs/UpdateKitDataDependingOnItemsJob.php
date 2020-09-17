<?php

namespace Modules\Items\Jobs;

use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateKitDataDependingOnItemsJob implements ShouldQueue
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
        $this->invoiceItem = $invoiceItem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $children = $this->invoiceItem->invoice->items()->where([['belong_to_kit',true],['parent_kit_id',$this->invoiceItem->id]])->get();



        $result['total'] = 0;
        $result['subtotal'] = 0;
        $result['tax'] = 0;
        $result['discount'] = 0;
        $result['net'] = 0;
        $items = $children;
        foreach ($items as $item){
            $result['total'] = $this->invoiceItem->moneyFormatter((float)$result['total'] + (float)$item['total']);
            $result['subtotal'] = $this->invoiceItem->moneyFormatter((float) $result['subtotal'] + (float)$item['subtotal']);
            $result['tax'] =  $this->invoiceItem->moneyFormatter((float)$result['tax'] + (float)$item['tax']);
            $result['discount'] = $this->invoiceItem->moneyFormatter((float) $result['discount'] + (float)$item['discount']);
            $result['net'] = $this->invoiceItem->moneyFormatter((float) $result['net'] + (float)$item['net']);
        }

        $this->invoiceItem->update($result);
    }
}
