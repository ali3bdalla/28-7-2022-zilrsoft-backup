<?php

namespace Modules\Sales\Jobs;

use AliAbdalla\Tafqeet\Core\Tafqeet;
use App\Account;
use App\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateSalesPaymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $methods; 
    private $invoice;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice,$methods = [])
    {
        $this->invoice = $invoice;
        $this->methods = $methods;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->methods != null ) 
        {
            foreach((array)$this->methods as $method)
            {
                $dbAccount = Account::find($method['id']);

                if($dbAccount != null)
                {
                    $payment = $dbAccount->paymentable()->create([
                        'organization_id' => $this->invoice->organization_id,
                        'creator_id' => $this->invoice->creator_id,
                        'user_id' => $this->invoice->user_id,
                        'invoice_id' => $this->invoice->id,
                        'amount_ar_words' => Tafqeet::arablic($this->invoice->moneyFormatter($method['amount'])),
                        'amount_en_words' => Tafqeet::arablic($this->invoice->moneyFormatter($method['amount'])),
                        'amount' => (float)$method['amount'],
                        'payment_type' => 'receipt'
                    ]);

                    $this->invoice->invoice_payments()->create([
                        'organization_id' => $this->invoice->organization_id,
                        'creator_id' => $this->invoice->creator_id,
                        'payment_id' => $payment->id,
                        'amount' => (float)$method['amount'],
                    ]);
                }
              
      
                
            }
        }
    }
}
