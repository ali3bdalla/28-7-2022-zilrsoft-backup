<?php

namespace Modules\Items\Jobs;

use App\Item;
use App\ItemSerials;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ValidateItemSerialsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Item
     */
    private $item;
    private $qty;
    private $serials;
    private $invoiceType;
    /**
     * @var int
     */
    private $index;
    /**
     * @var array
     */
    private $searchByStatuses;

    /**
     * Create a new job instance.
     *
     * @param Item $item
     * @param $qty
     * @param $serials
     * @param $invoiceType
     * @param array $searchByStatuses
     * @param int $index
     */
    public function __construct(Item $item,$qty,$serials,$invoiceType,$searchByStatuses = [],$index = 0)
    {
        //
        $this->item = $item;
        $this->qty = $qty;
        $this->serials = (array)$serials;
        $this->invoiceType = $invoiceType;
        $this->index = $index;
        $this->searchByStatuses = $searchByStatuses;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->invoiceType == 'sale')
            $this->validateSaleSerials();
        elseif($this->invoiceType == 'purchase')
            $this->validatePurchaseSerails();

    }


    public function validatePurchaseSerails()
    {
        if(count($this->serials) != $this->qty)
        {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                "items.{$this->index}.qty"=> ['item qty should equal serials count '],
            ]);
            throw $error;
        }


        foreach ($this->serials as $key => $serial)
        {
            $dbSerials = ItemSerials::where([['serial', $serial]])->whereIn('current_status',$this->searchByStatuses)->first();
            if($dbSerials != null)
            {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    "items.{$this->index}.serials.{$key}"=> ['serial already available'],
                ]);
                throw $error;
            }

        }

    }

    public function validateSaleSerials()
    {
        if(count($this->serials) != $this->qty)
        {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                "items.{$this->index}.qty"=> ['item qty should equal serials count '],
            ]);
            throw $error;
        }


        foreach ($this->serials as $key => $serial)
        {
            $dbSerials = ItemSerials::where([['serial', $serial]])->whereIn('current_status',$this->searchByStatuses)->first();
            if($dbSerials == null)
            {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    "items.{$this->index}.serials.{$key}"=> ['serial is not available'],
                ]);
                throw $error;
            }

        }


    }
}
