<?php

namespace App\Console\Commands;

use App\Jobs\Item\Cost\UpdateItemCostUsingPipelineJob;
use App\Models\Item;
use Illuminate\Console\Command;

class UpdateItemsQtyAndCostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_items_qty_and_cost_command';

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

        // $items = Item::all();


        dispatch(new UpdateItemCostUsingPipelineJob(Item::find(2263)));

        // foreach ($items as $key => $item) {
        // }
        //
    }
}
