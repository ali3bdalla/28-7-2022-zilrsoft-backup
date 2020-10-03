<?php

namespace App\Console\Commands\Seeder;

use App\Jobs\Items\Profit\UpdateItemProfitByInvoiceItem;
use App\Models\Item;
use Illuminate\Console\Command;

class UpdateServicesProfitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_services_profit_command';

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
        $services = Item::where('is_service',true)->get();

        foreach ($services as $service)
        {
            $service->update([
                'total_profits_amount' => 0
            ]);
            $pipeline = $service->pipeline()->whereIn('invoice_type',['sale','return_sale'])->get();
            foreach ($pipeline as $invoiceItem)
            {
                dispatch(new UpdateItemProfitByInvoiceItem($invoiceItem));
            }

        }
        //
    }
}
