<?php

namespace Modules\Items\Jobs;

use App\Invoice;
use App\Item;
use App\ItemSerials;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;

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
     * @var Invoice|null
     */
    private $invoice;

    /**
     * Create a new job instance.
     *
     * @param Item $item
     * @param $qty
     * @param $serials
     * @param $invoiceType
     * @param array $searchByStatuses
     * @param int $index
     * @param Invoice|null $invoice
     */
    public function __construct(Item $item, $qty, $serials, $invoiceType, $searchByStatuses = [], $index = 0, Invoice $invoice = null)
    {
        //
        $this->item = $item;
        $this->qty = $qty;
        $this->serials = (array)$serials;
        $this->invoiceType = $invoiceType;
        $this->index = $index;
        $this->searchByStatuses = $searchByStatuses;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->invoiceType == 'sale')
            $this->validateSaleSerials();
        elseif ($this->invoiceType == 'purchase')
            $this->validatePurchaseSerials();
        elseif ($this->invoiceType == 'return_sale')
            $this->validateReturnSaleSerials();
        elseif ($this->invoiceType == 'return_purchase')
            $this->validateReturnPurchaseSerials();
    }

    public function validateSaleSerials()
    {
        if (count($this->serials) != $this->qty) {
            $error = ValidationException::withMessages([
                "items.{$this->index}.qty" => ['item qty should equal serials count '],
            ]);
            throw $error;
        }


        foreach ($this->serials as $key => $serial) {
            $dbSerials = ItemSerials::where([['serial', $serial]])->whereIn('current_status', $this->searchByStatuses)->first();
            if ($dbSerials == null) {
                $error = ValidationException::withMessages([
                    "items.{$this->index}.serials.{$key}" => ['serial is not available'],
                ]);
                throw $error;
            }

        }


    }

    public function validatePurchaseSerials()
    {
        if (count($this->serials) != $this->qty) {
            $error = ValidationException::withMessages([
                "items.{$this->index}.qty" => ['item qty should equal serials count '],
            ]);
            throw $error;
        }


        foreach ($this->serials as $key => $serial) {
            $dbSerials = ItemSerials::where([['serial', $serial]])->whereIn('current_status', $this->searchByStatuses)->first();
            if ($dbSerials != null) {
                $error = ValidationException::withMessages([
                    "items.{$this->index}.serials.{$key}" => ['serial already available'],
                ]);
                throw $error;
            }

        }

    }

    public function validateReturnSaleSerials()
    {

        if (count($this->serials) != $this->qty) {
            $error = ValidationException::withMessages([
                "items.{$this->index}.qty" => ['item qty should equal serials count '],
            ]);
            throw $error;
        }


        foreach ($this->serials as $key => $serial) {
            $dbSerials = $this->item->serials()
                ->whereIn('current_status',$this->searchByStatuses)
                ->where([
                    ['serial', $serial],
                    ['sale_invoice_id', $this->invoice->id]
                ]
            )
            ->first();
            if ($dbSerials == null) {
                $error = ValidationException::withMessages([
                    "items.{$this->index}.serials.{$key}" => ['serial is not available as saled serial yet'],
                ]);
                throw $error;
            }

        }


    }


    public function validateReturnPurchaseSerials()
    {
//        dd(count($this->serials) ,$this->qty);

        if (count($this->serials) != $this->qty) {
            $error = ValidationException::withMessages([
                "items.{$this->index}.qty" => ['item qty should equal serials count '],
            ]);
            throw $error;
        }


        foreach ($this->serials as $key => $serial) {
            $dbSerials = $this->item->serials()->where([
                ['serial', $serial],
                ['purchase_invoice_id', $this->invoice->id]
            ])
                ->whereIn('current_status', $this->searchByStatuses)
                ->first();

            if ($dbSerials == null) {
                $error = ValidationException::withMessages([
                    "items.{$this->index}.serials.{$key}" => ['serial is not available as purchase serial yet'],
                ]);
                throw $error;
            }

        }


    }
}
