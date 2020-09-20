<?php

namespace App\Jobs\Items\Serial;

use App\Models\Invoice;
use App\Models\ItemSerials;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegisterSerialHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private  $itemSerial,$status,$invoice;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ItemSerials $itemSerial,$status,Invoice $invoice)
    {
        $this->itemSerial = $itemSerial;
        $this->status= $status;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->itemSerial->histories()->create([
            'organization_id' => $this->itemSerial->organization_id,
            'invoice_id' => $this->invoice->id,
            'user_id' => $this->invoice->user_id,
            'creator_id' => $this->invoice->creator_id,
            'event' => $this->status,
 
        ]);
    }
}
