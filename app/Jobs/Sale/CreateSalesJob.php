<?php

namespace App\Jobs\Sale;

use App\Jobs\Accounting\Sale\StoreSaleTransactionsJob;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Sales\Draft\SetDraftAsConvertedJob;
use App\Jobs\Sales\Items\StoreSaleItemsJob;
use App\Jobs\Sales\Order\UpdateOnlineOrderStatus;
use App\Jobs\Sales\Payment\StoreSalePaymentsJob;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
    public function __construct($clientId, $items = [], $paymentsMethods = [], $salesmanId = null, $quatationId = "", $isOnlineOrder = false, $aliasName = "")
    {
        $this->clientId = $clientId;
        $this->items = $items;
        $this->paymentsMethods = $paymentsMethods;
        $this->salesmanId = $salesmanId ?? auth('manager')->user()->id;
        $this->aliasName = $aliasName;
        $this->quatationId = $quatationId;
        $this->isOnlineOrder = $isOnlineOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws ValidationException
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

        dispatch_sync(new UpdateInvoiceNumberJob($invoice, 'S'));
        dispatch_sync(new StoreSaleItemsJob($invoice, (array)$this->items));
        dispatch_sync(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
        /**
         *
         * ========================================================
         * validate payments amount should be after updating invoice totals
         * ========================================================
         *
         */
        $paymentsMethods = $this->validatePaymentsAndGetPaymentMethods($invoice, $this->isOnlineOrder);
        dispatch_sync(new StoreSalePaymentsJob($invoice, $paymentsMethods));
        dispatch_sync(new StoreSaleTransactionsJob($invoice));
        if ($this->quatationId !== "") {
            dispatch_sync(new SetDraftAsConvertedJob($this->quatationId, $invoice->id));
            dispatch_sync(new UpdateOnlineOrderStatus($this->quatationId, $invoice));
        }
    }


    private function validatePaymentsAndGetPaymentMethods(Invoice $invoice, $isOnlineOrder = false)
    {

        if ($isOnlineOrder) {
            return [];
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
