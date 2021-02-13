<?php

namespace App\Jobs\Sale;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Jobs\Accounting\Sale\StoreSaleTransactionsJob;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Items\Serial\ValidateItemSerialJob;
use App\Jobs\Sales\Draft\SetDraftAsConvertedJob;
use App\Jobs\Sales\Expense\CreatePurchaseInvoiceForExpensesJob;
use App\Jobs\Sales\Items\StoreSaleItemsJob;
use App\Jobs\Sales\Order\UpdateOnlineOrderStatus;
use App\Jobs\Sales\Payment\StoreSalePaymentsJob;
use App\Models\Item;
use App\Models\ItemSerials;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateSalesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $clientId, $items, $paymentsMethods, $salesmanId, $aliasName, $quatationId, $isOnlineOrder;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($clientId, $items = [], $paymentsMethods = [], $salesmanId, $quatationId = "", $isOnlineOrder = false, $aliasName = "")
    {
        $this->clientId = $clientId;
        $this->items = $items;
        $this->paymentsMethods = $paymentsMethods;
        $this->salesmanId = $salesmanId;
        $this->aliasName = $aliasName;
        $this->quatationId = $quatationId;
        $this->isOnlineOrder = $isOnlineOrder;;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $authUser = auth()->user();

        $invoice = Invoice::create(
            [
                'invoice_type' => 'sale',
                'notes' => "",
                'creator_id' => $authUser->id,
                'organization_id' => $authUser->organization_id,
                'branch_id' => $authUser->branch_id,
                'department_id' => $authUser->department_id,
                'user_id' => $this->clientId,
                'managed_by_id' => $this->salesmanId,
            ]
        );
        $invoice->sale()->create(
            [
                'salesman_id' => $this->salesmanId,
                'client_id' => $this->salesmanId,
                'organization_id' => $authUser->organization_id,
                'invoice_type' => 'sale',
                'alice_name' => $this->aliasName,
                "prefix" => "S"
            ]
        );
        dispatch_now(new UpdateInvoiceNumberJob($invoice, 'S'));
        dispatch_now(new StoreSaleItemsJob($invoice, (array)$this->items));
        dispatch_now(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
        /**
         *
         * ========================================================
         * validate payments amount should be after updating invoice totals
         * ========================================================
         *
         */
        $paymentsMethods = $this->validatePaymentsAndGetPaymentMethods($invoice, $this->isOnlineOrder);
        dispatch_now(new StoreSalePaymentsJob($invoice, $paymentsMethods));
        dispatch_now(new StoreSaleTransactionsJob($invoice));
        dispatch_now(new SetDraftAsConvertedJob($this->quatationId, $invoice->id));
        dispatch_now(new UpdateOnlineOrderStatus($this->quatationId, $invoice));
    }



    private function validatePaymentsAndGetPaymentMethods(Invoice $invoice, $isOnlineOrder = false)
    {

        if ($isOnlineOrder) {
            return [];
            //            return $this->onlineOrderPayments();
        }
        $invoice = $invoice->fresh();

        $methodsCollects = collect($this->paymentsMethods);
        $paymentsMethodsCount = $methodsCollects->count();
        $totalPaidAmount = $methodsCollects->sum('amount');
        $user = User::find($this->clientId);
        if ($user->is_system_user) {

            if ($totalPaidAmount != $invoice->net) {
                if ($paymentsMethodsCount < 1) {
                    throw ValidationException::withMessages(['payments' => "summation of payments methods should match invoice net "]);
                } else {
                    $variationAmount = (float)$totalPaidAmount - (float)$invoice->fresh()->net;
                    $methods = $this->paymentsMethods;
                    $firstAmount = $methods[0]['amount'];
                    if ($variationAmount > 0) {
                        $newAmount = (float)$firstAmount - (float)abs($variationAmount);
                    } else {
                        $newAmount = (float)$firstAmount + (float)abs($variationAmount);
                    }

                    $methods[0]['amount'] = $newAmount;


                    return $methods;
                }
            }
        }
        return $this->paymentsMethods;
    }
}
