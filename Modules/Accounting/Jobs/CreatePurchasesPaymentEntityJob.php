<?php

namespace Modules\Accounting\Jobs;

use AliAbdalla\Tafqeet\Core\Tafqeet;
use App\Account;
use App\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreatePurchasesPaymentEntityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var Account
     */
    private $method;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var string
     */
    private $paymentType;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param int $amount
     * @param Account $method
     * @param string $paymentType
     */
    public function __construct(Invoice $invoice,Account $method,$amount = 0,$paymentType = 'receipt')
    {

        $this->invoice = $invoice;
        $this->method = $method;
        $this->amount =(float) $amount;
        $this->paymentType = $paymentType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if ($this->method != null){

            $payment = $this->method->paymentable()->create([
                'organization_id' => $this->invoice->organization_id,
                'creator_id' => $this->invoice->creator_id,
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'amount_ar_words' => Tafqeet::arablic($this->invoice->moneyFormatter($this->amount)),
                'amount_en_words' => Tafqeet::arablic($this->invoice->moneyFormatter($this->amount)),
                'amount' => $this->invoice->moneyFormatter($this->amount),
                'payment_type' => $this->paymentType
            ]);
        }else{
            $payment = $this->invoice->payments()->create([
                'organization_id' => $this->invoice->organization_id,
                'creator_id' => $this->invoice->creator_id,
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'amount_ar_words' => Tafqeet::arablic($this->invoice->moneyFormatter($this->amount)),
                'amount_en_words' => Tafqeet::arablic($this->invoice->moneyFormatter($this->amount)),
                'amount' => $this->invoice->moneyFormatter($this->amount),
                'payment_type' => $this->paymentType
            ]);
        }


        $this->invoice->invoice_payments()->create([
            'organization_id' => $this->invoice->organization_id,
            'creator_id' => $this->invoice->creator_id,
            'payment_id' => $payment->id,
            'amount' => $this->invoice->moneyFormatter($this->amount),
        ]);
    }
}
