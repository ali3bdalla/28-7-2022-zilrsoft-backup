<?php

namespace Modules\Accounting\Jobs;

use App\Invoice;
use App\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateSalesItemsCostEntityTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var TransactionsContainer
     */
    private $entity;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param TransactionsContainer $entity
     */
    public function __construct(Invoice $invoice,TransactionsContainer $entity)
    {
        //
        $this->invoice = $invoice;
        $this->entity = $entity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $manager_cogs_account = auth()->user()->toGetManagerAccount('cogs');
        $manager_products_sales_account = auth()->user()->toGetManagerAccount('products_sales');
        $manager_services_sales_account = auth()->user()->toGetManagerAccount('services_sales');
        $manager_other_services_sales_account = auth()->user()->toGetManagerAccount('other_services_sales');
        $manager_products_sales_discount_account = auth()->user()->toGetManagerAccount('products_sales_discount');
        $manager_services_sales_discount_account = auth()->user()->toGetManagerAccount('services_sales_discount');
        $manager_other_services_sales_discount_account = auth()->user()->toGetManagerAccount('other_services_sales_discount');
        $manager_stock_account = auth()->user()->toGetManagerAccount('stock');
        $total_cost =$this->invoice->moneyFormatter($this->invoice->transactions()->where('description','to_item')->sum('amount'));

//        echo($total_cost);
//        exit();
        if ($total_cost > 0){
            $manager_cogs_account->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'creditable_id' => $manager_stock_account->id,
                'creditable_type' => get_class($manager_stock_account),
                'amount' => $total_cost,
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_cogs',
            ]);
        }


        // to make sales transactions
        $products_sales_total = 0;
        $services_sales_total = 0;
        $other_services_sales_total = 0;
        $products_sales_total_discount = 0;
        $services_sales_total_discount = 0;
        $other_services_sales_total_discount = 0;


        $items = $this->getInvoiceItems();
        foreach ($items as $item){
            if ($item['item']['is_expense']){
                $other_services_sales_total = $other_services_sales_total + $item['total'];
                $other_services_sales_total_discount = $other_services_sales_total_discount + $item['discount'];
            }else if ($item['item']["is_service"]){
                $services_sales_total = $services_sales_total + $item['total'];
                $services_sales_total_discount = $services_sales_total_discount + $item['discount'];
            }else{
                $products_sales_total = $products_sales_total + $item['total'];
                $products_sales_total_discount = $products_sales_total_discount + $item['discount'];
            }
        }


        if ($products_sales_total > 0){
            $manager_products_sales_account->credit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'debitable_id' => $manager_stock_account->id,
                'debitable_type' => get_class($manager_stock_account),
                'amount' => $this->invoice->moneyFormatter($products_sales_total),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_products_sales',
            ]);
        }

        if ($services_sales_total > 0){
            $manager_services_sales_account->credit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'debitable_id' => $manager_stock_account->id,
                'debitable_type' => get_class($manager_stock_account),
                'amount' =>$this->invoice->moneyFormatter( $services_sales_total),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_services_sales',
            ]);
        }

        if ($other_services_sales_total > 0){
            $manager_other_services_sales_account->credit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'debitable_id' => $manager_stock_account->id,
                'debitable_type' => get_class($manager_stock_account),
                'amount' =>$this->invoice->moneyFormatter( $other_services_sales_total),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_other_services_sales',
            ]);
        }


        /// discounts
        if ($products_sales_total_discount > 0){
            $manager_products_sales_discount_account->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'creditable_id' => $manager_stock_account->id,
                'creditable_type' => get_class($manager_stock_account),
                'amount' => $this->invoice->moneyFormatter($products_sales_total_discount),
                'user_id' => $this->invoice->uesr_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_products_sales_discount',
            ]);
        }

        if ($services_sales_total_discount > 0){
            $manager_services_sales_discount_account->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'creditable_id' => $manager_stock_account->id,
                'creditable_type' => get_class($manager_stock_account),
                'amount' =>$this->invoice->moneyFormatter( $services_sales_total_discount),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_services_sales_discount',
            ]);
        }

        if ($other_services_sales_total_discount > 0){
            $manager_other_services_sales_discount_account->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'creditable_id' => $manager_stock_account->id,
                'creditable_type' => get_class($manager_stock_account),
                'amount' => $this->invoice->moneyFormatter($other_services_sales_total_discount),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_other_services_sales_discount',
            ]);
        }
    }


    public function getInvoiceItems()
    {
        return $this->invoice->items()->where('is_kit',false)->get();
    }
}
