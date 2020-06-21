<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Inventory\CreateBeginningRequest;
use App\Http\Requests\Accounting\Inventory\DatatableBeginningRequest;
use App\Http\Requests\Accounting\Inventory\ReturnBeginningRequest;
use App\Invoice;
use App\InvoicePayments;
use App\Payment;
use App\Transaction;
use App\TransactionsContainer;
use App\User;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryController extends Controller
{

    /**
     * InventoryController constructor.
     */
    public function __construct()
    {
        $this->middleware(['permission:manage inventory']);
    }

    /**
     * @return Factory|View
     */
    public function beginning_index()
    {
        return view('accounting.inventories.beginning.index');
    }

    /**
     * @param DatatableBeginningRequest $request
     *
     * @return mixed
     */
    public function beginning_datatable(DatatableBeginningRequest $request)
    {
        return $request->data();
    }

    /**
     * @return Factory|View
     */
    public function beginning_create()
    {
        $user = User::where([
            ['user_slug', 'beginning-inventory'],
            ['is_system_user', true]
        ])->first();

        $creator = auth()->user()->with('department', 'branch')->first();
        return view('accounting.inventories.beginning.create', compact('user', 'creator'));

    }

//
//		/**
//		 * @return Factory|View
//		 */
//		public function beginning_edit(Invoice $beginning)
//		{
//			return $beginning;
////			$user = User::where([
////				['user_slug','beginning-inventory'],
////				['is_system_user',true]
////			])->first();
////
////			$creator = auth()->user()->with('department','branch')->first();
////			return view('accounting.inventories.beginning.create',compact('user','creator'));
//
//		}
//
    /**
     * @param CreateBeginningRequest $request
     *
     * @throws Exception
     */
    public function beginning_store(CreateBeginningRequest $request)
    {
        return $request->save();
    }

    /**
     * @param Invoice $beginning
     */
    public function beginning_destroy(Invoice $beginning)
    {
//		    return $beginning;
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

                $item->item->stockMovement();
            }

            $beginning->items()->forceDelete();
            $beginning->forceDelete();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }

//
//
//			DB::beginTransaction();
//			try{
//				TransactionsContainer::where('invoice_id',$beginning->id)->forceDelete();
//				Transaction::where('invoice_id',$beginning->id)->forceDelete();
//				Payment::where('invoice_id',$beginning->id)->forceDelete();
//				InvoicePayments::where('invoice_id',$beginning->id)->forceDelete();
//				foreach ($beginning->items as $item){
//					$current_qty = $item->item->available_qty + $item['qty'];
////					'if ($current_qty < 0){
////						throw new ValidationException([
////							'qty'
////						]);
////					}'
//					if (!$item->is_kit){
//						$item->item->update([
//							'available_qty' => $current_qty,
//						]);
////						if ($item->item->is_need_serial){
////							$item->item->serials()->where('purchase_invoice_id',$beginning->id)->forceDelete();
////						}
//						$item->item->stockMovement();
//					}
//
//
//				}
//
//				$beginning->items()->forceDelete();
//				$beginning->forceDelete();
//
//				DB::commit();
//			}catch (Exception $exception){
//				DB::rollBack();
//			}
    }

    public function delete_sale(Invoice $beginning)
    {
        DB::beginTransaction();
        try {
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
//						$item->item->stockMovement();
                }


            }

            $beginning->items()->forceDelete();
            $beginning->forceDelete();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
    }


    public function delete_return_sale(Invoice $beginning)
    {
        DB::beginTransaction();
        try {
            TransactionsContainer::where('invoice_id', $beginning->id)->forceDelete();
            Transaction::where('invoice_id', $beginning->id)->forceDelete();
            Payment::where('invoice_id', $beginning->id)->forceDelete();
            InvoicePayments::where('invoice_id', $beginning->id)->forceDelete();
            foreach ($beginning->items as $item) {
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
                    $item->item->stockMovement();
                }


            }

            $beginning->items()->forceDelete();
            $beginning->forceDelete();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
    }


    public function delete_purchase(Invoice $beginning)
    {
//        return $beginning;
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
//                    $item->item->stockMovement();
                }


            }

            $beginning->items()->forceDelete();
            $beginning->forceDelete();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
    }

    /**
     * @param ReturnBeginningRequest $request
     *
     * @return array
     */
    public function beginning_return(ReturnBeginningRequest $request, Invoice $beginning)
    {
        return $request->makeReturn($beginning);
    }

}
