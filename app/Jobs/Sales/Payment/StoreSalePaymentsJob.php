<?php

namespace App\Jobs\Sales\Payment;

use App\Models\Account;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreSalePaymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var array
     */
    private $paymentsMethods;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param array $paymentsMethods
     */
    public function __construct(Invoice $invoice, $paymentsMethods = [])
    {
        //
        $this->invoice = $invoice;
        $this->paymentsMethods = $paymentsMethods;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->paymentsMethods != null) {
            foreach ((array)$this->paymentsMethods as $method) {
                $dbAccount = Account::find($method['id']);
                $clientAccount = Account::getSystemAccount("clients");
                if ($dbAccount != null) {
                    $dbAccount->payments()->create([
                        'organization_id' => $this->invoice->organization_id,
                        'creator_id' => $this->invoice->creator_id,
                        'user_id' => $this->invoice->user_id,
                        'invoice_id' => $this->invoice->id,
                        'amount' => (float)$method['amount'],
                        'payment_type' => 'receipt'
                    ]);
                }
            }
        }
    }
}
