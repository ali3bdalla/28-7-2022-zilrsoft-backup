<?php

namespace App\Console\Commands;

use App\Item;
use Illuminate\Console\Command;

class ChangeItemsVatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:change_items_vat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'change items vat to new vat';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $newSaleVat = 15;
        $newPurchaseVat = 15;
        $this->changePureItemsVat($newSaleVat,$newPurchaseVat);
//        $this->changeKitItemsVat($newSaleVat,$newPurchaseVat);
    }



    public function changeKitItemsVat($newSaleVat,$newPurchaseVat)
    {
        $items = Item::where([
            ['is_kit',true],
        ])->get();

        foreach ($items  as $item)
        {
            foreach ($item->items as $kitItem)
            {
                $newKitItemTax = $kitItem->item->vts * $kitItem->qty;

                dd($newKitItemTax,$kitItem->tax);
            }
        }
    }


    public function changePureItemsVat($newSaleVat,$newPurchaseVat)
    {
        $items = Item::where([
            ['is_kit',false],
        ])->get();

        foreach ($items  as $item)
        {
            //sale
            if($item->is_has_vts)
            {
                if($item->vts == 5)
                {
                    $newPriceWithTax =  (($item->price * $newSaleVat) / 100 ) + $item->price;
                    $item->update([
                        'vts' => $newSaleVat,
                        'price_with_tax' => $newPriceWithTax
                    ]);
                }
            }


            //purchase
            if($item->is_has_vtp)
            {
                if($item->vtp == 5)
                {
                    $item->update([
                        'vtp' => $newPurchaseVat
                    ]);
                }
            }

        }
    }
}
