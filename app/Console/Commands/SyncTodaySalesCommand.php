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
        $temp = $quickBooksDataService->Query("SELECT * FROM Payment WHERE CustomerRef='4602'");
        if (is_array($temp)) {
            foreach ($temp as $item) {
                if ($item->UnappliedAmt == $item->TotalAmt)
                    dispatch(new DeleteQuickbooksPaymentByIdJob($item->Id, $item->SyncToken, $manager));
            }
        }
        return 0;
    }
}
