<?php

namespace App\Http\Requests\Sales;

use App\Jobs\Accounting\Sale\StoreSaleTransactionsJob;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Items\Serial\ValidateItemSerialJob;
use App\Jobs\Sales\Draft\SetDraftAsConvertedJob;
use App\Jobs\Sales\Expense\CreatePurchaseInvoiceForExpensesJob;
use App\Jobs\Sales\Items\StoreSaleItemsJob;
use App\Jobs\Sales\Order\UpdateOnlineOrderStatus;
use App\Jobs\Sales\Payment\StoreSalePaymentsJob;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\ItemSerials;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreSaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [


            "items" => "required|array",
            "items.*.id" => "required|integer|organization_exists:App\Models\Item,id",
            "items.*.price" => "price|priceAndDiscount|min:0",
            "items.*.discount" => "priceAndDiscount",
            "items.*.qty" => "required|integer|min:1|salesItemQty",
//            "items.*.purchase_price" => "salesExpensesPurchasePrice|price|min:0",
            'items.*.serials' => 'array|newInvoiceItemSerials',
            'items.*.serials.*' => 'required|organization_exists:App\Models\ItemSerials,serial',
            'items.*.items.*.id' => 'required|organization_exists:App\Models\Item,id',
            'items.*.items.*.serials' => 'array',
            'items.*.items.*.serials.*' => 'required|organization_exists:App\Models\ItemSerials,serial',
            'items.*.items.*.qty' => 'required|integer|salesKitItemValidator',
            "client_id" => "required|integer|organization_exists:App\Models\User,id",
            "salesman_id" => "required|integer|organization_exists:App\Models\Manager,id",
            'methods' => 'array',
            'methods.*.id' => 'required|integer|organization_exists:App\Models\Account,id',
            'methods.*.amount' => 'required|numeric',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $isOnlineOrder = $this->isOnlineOrder();
            $this->requestValidation();
            $authUser = auth()->user();
            if ($this->has('without_creating_expenses_purchases') && $this->filled('without_creating_expenses_purchases')) {

            } else {
                dispatch(new CreatePurchaseInvoiceForExpensesJob($this->input('items')));
            }

            $invoice = Invoice::create(
                [
                    'invoice_type' => 'sale',
                    'notes' => $this->has('notes') ? $this->input('notes') : "",
                    'creator_id' => $authUser->id,
                    'organization_id' => $authUser->organization_id,
                    'branch_id' => $authUser->branch_id,
                    'department_id' => $authUser->department_id,
                    'user_id' => $this->input('client_id'),
                    'managed_by_id' => $this->input('salesman_id'),
                ]
            );
            $invoice->sale()->create(
                [
                    'salesman_id' => $this->input('salesman_id'),
                    'client_id' => $this->input('client_id'),
                    'organization_id' => $authUser->organization_id,
                    'invoice_type' => 'sale',
                    'alice_name' => $this->input('alice_name'),
                    "prefix" => "SAI-"
                ]
            );
            dispatch(new UpdateInvoiceNumberJob($invoice, 'SAI-'));
            dispatch(new StoreSaleItemsJob($invoice, (array)$this->input('items')));
            dispatch(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
            /**
             *
             * ========================================================
             * validate payments amount should be after updating invoice totals
             * ========================================================
             *
             */
            $paymentsMethods = $this->validatePaymentsAndGetPaymentMethods($invoice, $isOnlineOrder);
            dispatch(new StoreSalePaymentsJob($invoice, $paymentsMethods));
            dispatch(new StoreSaleTransactionsJob($invoice));
            dispatch(new SetDraftAsConvertedJob($this->input('quotation_id'), $invoice->id));
            dispatch(new UpdateOnlineOrderStatus($this->input('quotation_id'), $invoice));
            DB::commit();
            return $invoice;
        } catch (QueryException $queryException) {
            DB::rollBack();
            throw $queryException;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;

        }
    }

    private function isOnlineOrder()
    {
        $draft = Invoice::withoutGlobalScopes(['draft', 'manager'])->where('id', $this->input('quotation_id'))->first();
        if ($draft) {
            $order = Order::where('draft_id', $this->input('quotation_id'))->first();
            if ($order && $order->status == 'in_progress') {
                return true;
            }
        }

        return false;
    }

    private function validateSerials()
    {
        foreach ($this->input('items') as $item) {
            $dbItem = Item::find($item['id']);
            if ($dbItem->is_need_serial) {
                if (count($item['serials']) != $item['qty']) {
                    throw ValidationException::withMessages(['item_serial' => 'serials count don\'t  match qty']);
                }
                foreach ($item['serials'] as $serial) {
                    dispatch(new ValidateItemSerialJob($dbItem, $serial, ['sold', 'return_purchase']));
                }
            }
        }

    }

    private function validateKits()
    {
        foreach ($this->input('items') as $kitFrontEndData) {
            $dbKit = Item::find($kitFrontEndData['id']);
            // validate only if it's kit
            if ($dbKit->is_kit) {

                // loop on kit items
                foreach ($dbKit->items as $kitItem) {
                    // kit item qty should (kit qty * kit item qty)
                    $kitItemInvoiceQty = $kitFrontEndData['qty'] * $kitItem->qty;
                    // if qty not available throw an error
                    if ($kitItemInvoiceQty > $kitItem->item->available_qty) {
                        throw ValidationException::withMessages(['kit_item' => "invalid qty"]);
                    }
                    // if item need serial, serials array contain valid serials
                    if ($kitItem->item->is_need_serial) {

                        $serials = collect(collect($kitFrontEndData['items'])->where('id', $kitItem->item_id)->first())->get('serials');
                        if ($serials) {
                            if (count($serials) != $kitItemInvoiceQty) {
                                throw ValidationException::withMessages(['kit_item' => "invalid serials"]);
                            }

                            foreach ($serials as $serial) {
                                $itemSerial = ItemSerials::where(
                                    [
                                        ['serial', $serial],
                                        ['item_id', $kitItem->item_id],
                                    ]
                                )->whereIn('status', ['in_stock', 'return_sale'])->first();

                                if ($itemSerial == null) {
                                    throw ValidationException::withMessages(['kit_item' => "invalid serial"]);
                                }
                            }

                        } else {
                            throw ValidationException::withMessages(['kit_item' => "invalid serials"]);
                        }
                    }

                }
            }

        }
    }


    private function requestValidation()
    {

        $this->validateSerials();
        $this->validateKits();
        $this->validateQuantities($this->input('items'));
    }

    private function validateQuantities($items = [])
    {
        foreach ($items as $item) {
            $dbItem = Item::find($item['id']);
            if (!$dbItem->is_service && !$dbItem->is_expense && !$dbItem->is_kit) {
                if ((int)$dbItem->available_qty < (int)$item['qty']) {
                    throw ValidationException::withMessages(['item_available_quantity' => "you can't sale this items , qty not"]);
                }
            }
        }

    }

    private function validatePaymentsAndGetPaymentMethods(Invoice $invoice, $isOnlineOrder = false)
    {


        if ($isOnlineOrder) {
            return $this->onlineOrderPayments();
        }
        $invoice = $invoice->fresh();

        $methodsCollects = collect($this->input('methods'));
        $paymentsMethodsCount = $methodsCollects->count();
        $totalPaidAmount = $methodsCollects->sum('amount');
        $user = User::find($this->input('client_id'));
        if ($user->is_system_user) {

            if ($totalPaidAmount != $invoice->net) {
                if ($paymentsMethodsCount < 1) {
                    throw ValidationException::withMessages(['payments' => "summation of payments methods should match invoice net "]);
                } else {
                    $variationAmount = (float)$totalPaidAmount - (float)$invoice->fresh()->net;
                    $methods = $this->input('methods');
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
        return $this->input('methods');
    }


    public function onlineOrderPayments()
    {
        $draft = Invoice::withoutGlobalScopes(['draft', 'manager'])->where('id', $this->input('quotation_id'))->first();
        if ($draft) {
            $order = Order::where('draft_id', $this->input('quotation_id'))->first();
            if ($order && $order->status == 'in_progress') {
                if ($order->paymentDetail) {
                    $account = $order->paymentDetail->receivedBank->account;
                    $account = $account->toArray();
                    $account['amount'] = $order->net;
                    return $account;
                }
            }
        }

        throw ValidationException::withMessages(['payment' => 'order payment not exists']);
    }


}
