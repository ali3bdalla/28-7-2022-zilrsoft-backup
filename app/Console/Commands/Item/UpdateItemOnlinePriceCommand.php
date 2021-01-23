<?php

namespace App\Console\Commands\Item;

use App\Models\Item;
use Illuminate\Console\Command;

class UpdateItemOnlinePriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateItemOnlinePriceCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    
        $items = Item::all();
        foreach ($items as $item) {

            foreach ($item->filters as $key => $value) {
                $item->tags()->create([
                    'tag' => $value->value->name
                ]);

                $item->tags()->create([
                    'tag' => $value->value->ar_name
                ]);
            }
        }


//	    item.cost * (1 + (item.vtp / 100)
        // Item::where('id','>',0)->each(function($item) {
	    //     $item->update([
	    //     	'online_price' => $item->price_with_tax,
        //         'online_offer_price' => $item->cost * (1 + ($item->vtp / 100)),
        //         'shipping_discount' => rand(1,5),
        //         'weight' => rand(1,3)
	    //     ]);
        // });
    }
}
