<?php

namespace Modules\Items\Jobs;

use App\Invoice;
use App\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChangeItemSerialsStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Item
     */
    private $item;
    private $serials;
    /**
     * @var string
     */
    /**
     * @var array
     */
    private $searchByStatuses;
    /**
     * @var string
     */
    private $changeTo;
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * Create a new job instance.
     *
     * @param Item $item
     * @param Invoice $invoice
     * @param $serials
     * @param array $searchByStatuses
     * @param string $changeTo
     */
    public function __construct(Item $item, Invoice $invoice, $serials, $searchByStatuses = [], $changeTo = 'saled')
    {
        //
        $this->item = $item;
        $this->serials = (array)$serials;
        $this->searchByStatuses = $searchByStatuses;
        $this->changeTo = $changeTo;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        foreach ($this->serials as $key => $serial) {

            $data = [
                'current_status' => $this->changeTo
            ];

            if ($this->changeTo == 'saled') {
                $data['sale_invoice_id'] = $this->invoice->id;
                $data['saled_by'] = $this->invoice->user_id;
            } else if ($this->changeTo == 'r_sale') {
                $data['r_sale_invoice_id'] = $this->invoice->id;
            } else if ($this->changeTo == 'purchase') {
                $data['purchase_invoice_id'] = $this->invoice->id;
            } else if ($this->changeTo == 'r_purchase') {
                $data['r_purchase_invoice_id'] = $this->invoice->id;
            }
            $serialDB = $this->item->serials()->where([['serial', $serial]])->whereIn('current_status', $this->searchByStatuses)->first();

            if ($serialDB != null) {
                $serialDB->update($data);

                if ($this->invoice->invoice_type != 'pending_purchase') {
                    $this->invoice->serial_history()->create([
                        'event' => $this->invoice->invoice_type,
                        'organization_id' => auth()->user()->organization_id,
                        'creator_id' => auth()->user()->id,
                        'serial_id' => $serialDB->id,
                        'user_id' => $this->invoice->user_id
                    ]);
                }
            }
        }

    }
}
