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
use App\Models\Manager;
use App\Models\Order;
use App\Models\User;
use App\Scopes\DraftScope;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class StoreSaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.price' => 'price|min:0',
            'items.*.discount' => 'price',
            'items.*.qty' => 'required|quantity|min:1|salesItemQty',
            'items.*.serials' => 'array|newInvoiceItemSerials',
            'items.*.serials.*' => 'required|exists:item_serials,serial',
            'items.*.items' => 'nullable|array',
            'items.*.items.*.id' => 'required|exists:items,id',
            'items.*.items.*.serials' => 'array',
            'items.*.items.*.serials.*' => 'required|exists:item_serials,serial',
            'items.*.items.*.qty' => 'required|quantity',
            'client_id' => 'required|integer|exists:users,id',
            'salesman_id' => 'required|integer|exists:managers,id',
            'methods' => 'array',
            'methods.*.id' => 'required|integer|exists:accounts,id',
            'methods.*.amount' => 'required|numeric',
        ];
    }
    public function getInvoiceClient(): User
    {
        return User::find($this->input('client_id'));
    }
    public function getSalesManId()
    {
        return $this->input('salesman_id');
    }
    public function getPaymentMethods(): array
    {
        return (array)$this->input('methods');
    }

    public function getItems(): array
    {
        return (array)$this->input('items');
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws Throwable|ValidationException
     */
    public function store()
    {
        DB::beginTransaction();

        try {
            $isOnlineOrder = $this->isOnlineOrder();
            $this->requestValidation();
            $authUser = $this->loggedUser();
            if (!$this->has('without_creating_expenses_purchases') || !$this->filled('without_creating_expenses_purchases')) {
                dispatch_sync(new CreatePurchaseInvoiceForExpensesJob($this->input('items')));
            }

            $invoice = Invoice::create(
                [
                    'invoice_type' => 'sale',
                    'notes' => $this->has('notes') ? $this->input('notes') : '',
                    'creator_id' => $authUser->id,
                    'organization_id' => $authUser->organization_id,
                    'branch_id' => $authUser->branch_id,
                    'department_id' => $authUser->department_id,
                    'user_id' => $this->input('client_id'),
                    'user_alice_name' => $this->input('alice_name'),
                    'managed_by_id' => $this->input('salesman_id'),
                ]
            );

            dispatch_sync(new UpdateInvoiceNumberJob($invoice, 'S'));
            dispatch_sync(new StoreSaleItemsJob($invoice, (array)$this->input('items'), false, null, $isOnlineOrder));
            dispatch_sync(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
            /**
             * ========================================================
             * validate payments amount should be after updating invoice totals
             * ========================================================.
             */
            $paymentsMethods = $this->validatePaymentsAndGetPaymentMethods($invoice, $isOnlineOrder);
            dispatch_sync(new StoreSalePaymentsJob($invoice, $paymentsMethods));
            dispatch_sync(new StoreSaleTransactionsJob($invoice));
            dispatch_sync(new SetDraftAsConvertedJob($this->input('quotation_id'), $invoice->id));
            dispatch_sync(new UpdateOnlineOrderStatus($this->input('quotation_id'), $invoice));
            DB::commit();
            return $invoice;
        } catch (QueryException | ValidationException | Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function isOnlineOrder(): bool
    {
        $draft = Invoice::withoutGlobalScopes([DraftScope::class])->where('id', $this->input('quotation_id'))->first();
        if ($draft) {
            $order = Order::where('draft_id', $this->input('quotation_id'))->first();
            if ($order && 'in_progress' == $order->status) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws ValidationException
     */
    private function requestValidation()
    {
        $this->validateSerials();
        $this->validateKits();
        $this->validateQuantities($this->input('items'));
    }

    /**
     * @throws ValidationException
     */
    private function validateSerials()
    {
        foreach ($this->input('items') as $item) {
            $dbItem = Item::find($item['id']);
            if ($dbItem->is_need_serial) {
                if (count($item['serials']) != $item['qty']) {
                    throw ValidationException::withMessages(['item_serial' => 'serials count don\'t  match qty']);
                }
                foreach ($item['serials'] as $serial) {
                    dispatch_sync(new ValidateItemSerialJob($dbItem, $serial, ['sold', 'return_purchase'], ['in_stock', 'return_sale']));
                }
            }
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateKits()
    {
        foreach ($this->input('items') as $kitFrontEndData) {
            $dbKit = Item::find($kitFrontEndData['id']);
            if ($dbKit->is_kit) {
                foreach ($dbKit->items as $kitItem) {
                    $kitItemInvoiceQty = $kitFrontEndData['qty'] * $kitItem->qty;
                    if ($kitItemInvoiceQty > $kitItem->item->available_qty) {
                        throw ValidationException::withMessages(['kit_item' => 'invalid qty']);
                    }
                    if ($kitItem->item->is_need_serial) {
                        $serials = collect(collect($kitFrontEndData['items'])->where('id', $kitItem->item_id)->first())->get('serials');
                        if ($serials) {
                            if (count($serials) != $kitItemInvoiceQty) {
                                throw ValidationException::withMessages(['kit_item' => 'invalid serials']);
                            }

                            foreach ($serials as $serial) {
                                $itemSerial = ItemSerials::whereSerial($serial)
                                    ->whereItemId($kitItem->item_id)
                                    ->whereIn('status', ['in_stock', 'return_sale'])
                                    ->first();
                                if (null == $itemSerial) {
                                    throw ValidationException::withMessages(['kit_item' => 'invalid serial']);
                                }
                            }
                        } else {
                            throw ValidationException::withMessages(['kit_item' => 'invalid serials']);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param mixed $items
     *
     * @throws ValidationException
     */
    private function validateQuantities($items = [])
    {
        foreach ($items as $item) {
            $dbItem = Item::find($item['id']);
            if (!$dbItem->is_service && !$dbItem->is_expense && !$dbItem->is_kit) {
                if ((float)$dbItem->available_qty < (float)$item['qty']) {
                    throw ValidationException::withMessages(['item_available_quantity' => "you can't sale this items , qty not"]);
                }
            }
        }
    }

    public function loggedUser(): Manager
    {
        return parent::user();
    }

    /**
     * @param mixed $isOnlineOrder
     *
     * @throws ValidationException
     * @throws Throwable
     */
    private function validatePaymentsAndGetPaymentMethods(Invoice $invoice, $isOnlineOrder = false)
    {
        if ($isOnlineOrder) {
            return [];
        }
        $invoice = $invoice->fresh();

        $methodsCollects = collect($this->input('methods'));
        $paymentsMethodsCount = $methodsCollects->count();
        $totalPaidAmount = $methodsCollects->sum('amount');
        $user = User::find($this->input('client_id'));
        if ($user->is_system_user) {
            if ($totalPaidAmount != $invoice->net) {
                throw_if($paymentsMethodsCount < 1, ValidationException::withMessages(['payments' => 'summation of payments methods should match invoice net ']));
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

        return $this->input('methods');
    }
}
