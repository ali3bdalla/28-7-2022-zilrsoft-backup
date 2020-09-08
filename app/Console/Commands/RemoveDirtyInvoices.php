<?php

namespace App\Console\Commands;

use App\Invoice;
use Illuminate\Console\Command;
use Modules\Purchases\Jobs\DeletePurchaseInvoiceJob;
use Modules\Sales\Jobs\DeleteSaleInvoiceJob;

class RemoveDirtyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:remove_dirty_invoices_command';

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


        $invoices = Invoice::find([
            9954, 12784, 13033, 13034, 13035, 13040, 13041, 13042, 13043, 13044, 13045, 13046, 13047,
            13048, 13049, 13050, 13051, 13052, 13053, 13054, 13058, 13059, 13060, 13061, 13062, 13064
        ]);
        foreach ($invoices as $beginning) {
            dispatch(new DeleteSaleInvoiceJob($beginning));
        }

        // r_sale
        $invoices = Invoice::whereIn('id', [1090, 13036, 13037, 13038, 13039, 13063, 13074, 14032, 13334, 14134, 14664, 15058])->get();
        foreach ($invoices as $beginning) {
            dispatch(new DeleteSaleInvoiceJob($beginning));
        }

        // purchase
        $invoices = Invoice::whereIn('id', [1673, 1632, 1981, 2932, 9449, 14450])->get();
        foreach ($invoices as $beginning) {
            dispatch(new DeletePurchaseInvoiceJob($beginning));
        }

        $invoices = Invoice::find([1090, 13037, 13038, 13039, 13074, 14032, 14134, 14664]);
        foreach ($invoices as $beginning) {
            dispatch(new DeletePurchaseInvoiceJob($beginning));
        }


    }
}
