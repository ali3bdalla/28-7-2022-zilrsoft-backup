<?php

namespace App\Console\Commands\InvoiceItem;

use App\Models\InvoiceItems;
use Illuminate\Console\Command;

class UpdateItemsCostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_items_cost_command';

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
        //
        

        // $items= InvoiceItems::where('')
    }
}
