<?php

namespace Modules\Accounting\Jobs;

use App\Account;
use App\Invoice;
use App\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;
use Modules\Users\Jobs\UpdateUserBalanceJob;

class CreateReturnSalesEntityTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var TransactionsContainer
     */
    private $entity;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var array
     */
    private $paymentMethods;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $entity
     * @param Invoice $invoice
     * @param array $paymentMethods
     */
    public function __construct(TransactionsContainer $entity, Invoice $invoice, $paymentMethods = [])
    {
        //
        $this->entity = $entity;
        $this->invoice = $invoice;
        $this->paymentMethods = $paymentMethods;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $creatorStock = Account::where("slug", 'stock')->first();
        $clientAccount = Account::where('slug', 'clients')->first();

        $paymentsGatewayPaidAmount = $this->getPaymentsGatewayPaidAmount();
        $userId = $this->invoice->user_id;
        $invoiceNetAmount = $this->invoice->net;
        $clientAccount->credit_transaction()->create([
            'creator_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization_id,
            'amount' => $invoiceNetAmount,
            'user_id' => $userId,
            'invoice_id' => $this->invoice->id,
            'container_id' => $this->entity->id,
            'description' => 'client_balance'
        ]);

        $this->addTaxTransactions($creatorStock);

        if ($paymentsGatewayPaidAmount < $invoiceNetAmount) {
            $amount = (float)($invoiceNetAmount) - (float)($paymentsGatewayPaidAmount);
            if($amount < 0)
            {
                $error = ValidationException::withMessages([
                    "net" => ['total paid gateways amount can\'t be more than invoice net'],
                ]);
                throw $error;
            }
            if (!$this->invoice->user()->is_system_user) {
//                dd($amount);
                $this->invoice->user()->credit_transaction()->create([
                    'creator_id' => auth()->user()->id,
                    'organization_id' => auth()->user()->organization_id,
                    'debitable_id' => $creatorStock->id,
                    'debitable_type' => get_class($creatorStock),
                    'amount' => $this->invoice->moneyFormatter($amount),
                    'user_id' => $userId,
                    'invoice_id' => $this->invoice->id,
                    'container_id' => $this->entity->id,
                    'description' => 'to_stock',
                ]);
                dispatch(new UpdateUserBalanceJob($this->invoice->user(), 'client_balance', 'increase', $amount));
            } else {
                if ($this->paymentMethods != null && count($this->paymentMethods) >= 1) {
                    $this->paymentMethods[0]["amount"] = $this->paymentMethods[0]["amount"] + $amount;
                } else {
                    $error = ValidationException::withMessages([
                        "net" => ['You should add at lest one payment method for non-registered customers'],
                    ]);
                    throw $error;
                }

            }
        }

        if ($this->paymentMethods != null) {
            foreach ($this->paymentMethods as $method) {
//                dd((float)$method['amount']);
                if ((float)$method['amount'] > 0) {
                    $gateway = Account::find($method['id']);
                    $gateway->credit_transaction()->create([
                        'creator_id' => auth()->user()->id,
                        'organization_id' => auth()->user()->organization_id,
                        'debitable_id' => $creatorStock->id,
                        'debitable_type' => get_class($creatorStock),
                        'amount' => $this->invoice->moneyFormatter($method['amount']),
                        'user_id' => $userId,
                        'invoice_id' => $this->invoice->id,
                        'container_id' => $this->entity->id,
                        'description' => 'to_gateway',
                    ]);
                    $clientAccount->debit_transaction()->create([
                        'creator_id' => auth()->user()->id,
                        'organization_id' => auth()->user()->organization_id,
                        'amount' => $this->invoice->moneyFormatter($method['amount']),
                        'user_id' => $userId,
                        'invoice_id' => $this->invoice->id,
                        'container_id' => $this->entity->id,
                        'description' => 'client_balance'
                    ]);

//                    dd(1);
                    dispatch(new CreateSalesPaymentEntityJob($this->invoice, $gateway, $method['amount'], 'payment'));
                }
            }
        }

        dispatch(new CreateReturnSalesItemsCostEntityTransactionsJob($this->invoice, $this->entity));
    }

    private function getPaymentsGatewayPaidAmount()
    {
        $paymentsGatewayPaidAmount = 0;
        if ($this->paymentMethods !== null) {
            foreach ($this->paymentMethods as $method) {
                if ((float)$method['amount'] > 0) {
                    $paymentsGatewayPaidAmount = (float)$paymentsGatewayPaidAmount + (float)$method['amount'];
                }
            }
        }
        return (float)$paymentsGatewayPaidAmount;
    }


    private function addTaxTransactions(Account $stockAccount)
    {
        $taxAccount = Account::where('slug', 'vat')->first();
        $gatewayAccounts = auth()->user()->gateways()->where(
            'is_gateway', true
        )->get();
        if (count($gatewayAccounts) === 0) {
            $userGatewayAccount = Account::where([
                ['is_system_account', true],
                ['slug', 'temp_reseller_account'],
            ])->first();
        } else {
            $userGatewayAccount = $gatewayAccounts[0];
        }

        $expensesTax = $this->invoice->expenses()->sum('tax');
        $tax = $expensesTax + $this->invoice->tax;


        if ($tax > 0) {
            $userGatewayAccount->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'creditable_id' => $stockAccount->id,
                'creditable_type' => get_class($stockAccount),
                'amount' => $tax,
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_tax',
            ]);
        }
        $sum = $this->invoice->expenses()->where('with_net', 0)->sum('amount');

        if ($sum > 0) {
            $cash_paid_before = $this->invoice->transactions()->where([['creditable_type', 'App\Account'], ['creditable_id',
                $userGatewayAccount->id]])->first();
            if (!empty($cash_paid_before)) {
                $new_amount = $cash_paid_before->amount + $sum;
                $cash_paid_before->update([
                    'amount' => $new_amount
                ]);
            } else {
                $taxAccount->debit_transaction()->create([
                    'creator_id' => auth()->user()->id,
                    'organization_id' => auth()->user()->organization_id,
                    'creditable_id' => $userGatewayAccount->id,
                    'creditable_type' => get_class($userGatewayAccount),
                    'amount' => $sum,
                    'user_id' => $this->invoice->user_id,
                    'invoice_id' => $this->invoice->id,
                    'container_id' => $this->entity->id,
                    'description' => 'to_gateway',
                ]);
            }

        }


    }


}
