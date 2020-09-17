<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\InvoicePayments;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class MassTransactionFixCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fix-mass-transactions';

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

        DB::beginTransaction();
        try {
            $invoices = Invoice::find([9954,12784,13033,13034,13035,13040,13041,13042,13043,13044,13045,13046,13047,
            13048,13049,13050,13051,13052,13053,13054,13058,13059,13060,13061,13062,13064]);
            foreach ($invoices as $beginning) {
                TransactionsContainer::where('invoice_id', $beginning->id)->forceDelete();
                Transaction::where('invoice_id', $beginning->id)->forceDelete();
                Payment::where('invoice_id', $beginning->id)->forceDelete();
                InvoicePayments::where('invoice_id', $beginning->id)->forceDelete();
                foreach ($beginning->items as $item) {
                    $current_qty = $item->item->available_qty + $item['qty'];
    //					'if ($current_qty < 0){
    //						throw new ValidationException([
    //							'qty'
    //						]);
    //					}'
                    if (!$item->is_kit) {
                        $item->item->update([
                            'available_qty' => $current_qty,
                        ]);
                        if ($item->item->is_need_serial) {
                            $item->item->serials()->where('sale_invoice_id', $beginning->id)->update([
                                'current_status' => "available"
                            ]);
                        }
    						// $item->item->stockMovement();
                    }


                }

                $beginning->items()->forceDelete();
                $beginning->forceDelete();
            }
            

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        

        DB::beginTransaction();
        try {
        // r_sale
        $invoices = Invoice::whereIn('id',[1090,13036,13037,13038,13039,13063,13074,14032,13334,14134,14664,15058])->get();
        // dd($invoices);
        foreach ($invoices as $beginning) {
                
                TransactionsContainer::where('invoice_id', $beginning->id)->forceDelete();
                Transaction::where('invoice_id', $beginning->id)->forceDelete();
                Payment::where('invoice_id', $beginning->id)->forceDelete();
                InvoicePayments::where('invoice_id', $beginning->id)->forceDelete();
                foreach ($beginning->items as $item) {
                    if($item->item != null){
                    $current_qty = $item->item->available_qty - $item['qty'];
                //					'if ($current_qty < 0){
                //						throw new ValidationException([
                //							'qty'
                //						]);
                //					}'
                    if (!$item->is_kit) {
                        $item->item->update([
                            'available_qty' => $current_qty,
                        ]);
                        if ($item->item->is_need_serial) {
                            $item->item->serials()->where('r_sale_invoice_id', $beginning->id)->update([
                                'current_status' => "saled"
                            ]);
                        }
                        // $item->item->stockMovement();
                    }

                }

                }

                $beginning->items()->forceDelete();
                $beginning->forceDelete();
                }

                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
      

        // exit();



        // purchase
        $invoices = Invoice::whereIn('id',[1673,1632,1981,2932,9449,14450])->get();

        foreach($invoices as $beginning)
        {
            DB::beginTransaction();
            try {
                TransactionsContainer::where('invoice_id', $beginning->id)->forceDelete();
                Transaction::where('invoice_id', $beginning->id)->forceDelete();
                Payment::where('invoice_id', $beginning->id)->forceDelete();
                InvoicePayments::where('invoice_id', $beginning->id)->forceDelete();
                foreach ($beginning->items as $item) {
                    $current_qty = $item->item->available_qty - $item['qty'];
                    if (!$item->is_kit) {
                        $item->item->update([
                            'available_qty' => $current_qty,
                        ]);
                        if ($item->item->is_need_serial) {
                            $item->item->serials()->where('purchase_invoice_id', $beginning->id)->forceDelete();
                        }
                    //    $item->item->stockMovement();
                    }
    
    
                }
    
                $beginning->items()->forceDelete();
                $beginning->forceDelete();
    
                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();
                 throw $exception;
            }
        }
        

    $invoices = Invoice::whereIn('id',[1090,13037,13038,13039,13074,14032,14134,14664])->get();
    foreach($invoices as $beginning)
    {
        DB::beginTransaction();
        try {

            TransactionsContainer::where('invoice_id', $beginning->id)->forceDelete();
            Transaction::where('invoice_id', $beginning->id)->forceDelete();
            Payment::where('invoice_id', $beginning->id)->forceDelete();
            InvoicePayments::where('invoice_id', $beginning->id)->forceDelete();
            foreach ($beginning->items as $item) {
                $current_qty = $item->item->available_qty - $item['qty'];
//					if ($current_qty < 0){
//						throw new ValidationException([
//							'qty'
//						]);
//					}
                $item->item->update([
                    'available_qty' => $current_qty,
                ]);
                if ($item->item->is_need_serial) {
                    $item->item->serials()->where('purchase_invoice_id', $beginning->id)->forceDelete();
                }

                // $item->item->stockMovement();
            }

            $beginning->items()->forceDelete();
            $beginning->forceDelete();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
        // $invoices = Invoice::whereIn('invoice_type',['r_sale'])->get();

        // // $mass = [];
        // foreach($invoices as $invoice)
        // {

        // //     [
        //         // 'to_stock',
        //         // 'to_gateway',
        //         // 'to_item',
        //         // 'to_products_sales',
        //         // 'to_services_sales',
        //         // 'to_other_services_sales',
        //         // 'client_balance',
                
        // // ]
        //     $credit = $invoice->transactions()->whereIn('description',[
        //         'to_stock',
        //         'to_cogs',
        //         'to_gateway',
        //         'to_products_sales_discount',
        //         'to_services_sales_discount',
        //         'to_other_services_sales_discount',
        //         // 'client_balance',
        //     ])->sum('amount');

        //     // whereIn('description',[
        //     //     'to_cogs',
        //     //     'to_tax',
        //     //     'to_products_sales_discount',
        //     //     'to_services_sales_discount',
        //     //     'to_other_services_sales_discount',
        //     // ])

        //     $debit = $invoice->transactions()->whereIn('description',[
        //         'to_item',
        //         'to_tax',
        //         'to_products_sales',
        //         'to_services_sales',
        //         'to_other_services_sales',
        //     ])->sum('amount');

            
        //     $def = $credit - $debit;

        //     if(abs($def) > 5)
        //     {
        //         echo  $invoice->id . ' - ' . $credit . '  - ' . $debit .  "\n" ;
        //     }
        //     // else
        //     // {
        //     //     // echo $container->id . "\n";
        //     //     $mass[] = $container->id;
        //     // }
        // }


        // return $mass;

        
    }
}
