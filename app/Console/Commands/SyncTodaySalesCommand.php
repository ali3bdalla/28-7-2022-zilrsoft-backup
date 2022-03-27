<?php

namespace App\Console\Commands;

use App\Enums\InvoiceTypeEnum;
use App\Enums\VoucherTypeEnum;
use App\Jobs\QuickBooks\BillPaymentQuickBooksSyncJob;
use App\Jobs\QuickBooks\BillQuickBooksSyncJob;
use App\Jobs\QuickBooks\DeletePaymentQuickBooksSyncJob;
use App\Jobs\QuickBooks\DeleteQuickbooksPaymentByIdJob;
use App\Jobs\QuickBooks\DeleteSalesQuickBooksSyncJob;
use App\Jobs\QuickBooks\PaymentQuickBooksSyncJob;
use App\Jobs\QuickBooks\RefundBillQuickBooksSyncJob;
use App\Jobs\QuickBooks\SalesQuickBooksSyncJob;
use App\Models\Invoice;
use App\Models\Manager;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use QuickBooksOnline\API\Facades\Payment;

class SyncTodaySalesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncTodaySalesCommand';

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
     * @return int
     */
    public function handle(): int
    {
        $manager = Manager::whereEmail("ali@msbrshop.com")->first();
        $quickBooksDataService = app("quickbooksDataService", [
            "manager" => $manager
        ]);
        $count = 0;
        while ($count < 7000) {
            $temp = $quickBooksDataService->Query("SELECT * FROM Payment WHERE CustomerRef='4602'", $count, ($count + 100));
            foreach ($temp as $item) {
                if ($item->UnappliedAmt == $item->TotalAmt)
                    dispatch(new DeleteQuickbooksPaymentByIdJob($item->Id, $item->SyncToken, $manager));
            }
            $count = $count + 100;
            echo "start at : " . $count . "\n";
            sleep(1);
        }

//        $vouchers = Voucher::query()->whereHas("user",function($user){
//            return $user->whereNotNull('quickbooks_customer_id');
//        })
//            ->whereNull("quickbooks_id")
//            ->where('payment_type',VoucherTypeEnum::receipt())->whereYear("created_at", ">=", "2021")->where('organization_id',1)->get();
//        foreach($vouchers as $voucher) {
//            dispatch(new PaymentQuickBooksSyncJob($voucher,$manager));
//        $invoices = Invoice::query()->whereNotNull('quickbooks_id')->whereYear("created_at", "<", "2021")->whereIn('invoice_type', [InvoiceTypeEnum::sale()])->where("organization_id", 1)->get();
////        }
//        foreach ($invoices as $invoice) {
//            dispatch_sync(new DeleteSalesQuickBooksSyncJob($invoice, $manager));
//            echo $invoice->invoice_number . "\n";
//        }
        $vouchers = Voucher::query()->whereHas("user", function ($user) {
            return $user->whereNotNull('quickbooks_customer_id');
        })->whereNotNull("quickbooks_id")->where('payment_type', VoucherTypeEnum::receipt())->whereYear("created_at", ">=", "2021")->where('organization_id', 1)->get();
        foreach ($vouchers as $voucher) {
            dispatch(new DeletePaymentQuickBooksSyncJob($voucher, $manager));
        }
        return 0;
    }
}
