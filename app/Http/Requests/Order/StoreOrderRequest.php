<?php

namespace App\Http\Requests\Order;

use App\Events\Order\OrderCreatedEvent;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Order\CreateOrderPdfSnapshotJob;
use App\Jobs\Order\HoldItemQtyJob;
use App\Jobs\Sales\Items\StoreSaleItemsJob;
use App\Jobs\Sales\Order\CreateSalesOrderJob;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Manager;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Rules\ExistsRule;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreOrderRequest extends FormRequest
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
            "items.*.quantity" => "required|integer|min:1",
            'shipping_address_id' => ['required', new ExistsRule(ShippingAddress::class)],
            'shipping_method_id' => ['required', new ExistsRule(ShippingMethod::class)],
            'payment_method_id' => ['required'],
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
            $this->validateQuantities($this->input('items'));
            $authUser = Manager::find(1);
            $authClient = $this->user('client');

            
            $invoice = Invoice::create(
                [
                    'invoice_type' => 'sale',
                    'notes' => "",
                    'creator_id' => $authUser->id,
                    'organization_id' => $authUser->organization_id,
                    'branch_id' => $authUser->branch_id,
                    'department_id' => $authUser->department_id,
                    'user_id' => $authClient->id,
                    'managed_by_id' => $authUser->id,
                    'is_online' => true,
                    'is_draft' => true
                ]
            );
            $sale = $invoice->sale()->create(
                [
                    'salesman_id' => $authUser->id,
                    'client_id' => $authClient->id,
                    'organization_id' => $authUser->organization_id,
                    'invoice_type' => 'sale',
                    'alice_name' => '',
                    "prefix" => "ONL-",
                    'is_draft' => true
                ]
            );
            dispatch(new UpdateInvoiceNumberJob($invoice, 'ONL-'));
            dispatch(new StoreSaleItemsJob($invoice, (array)$this->input('items'), true, $authUser, true));
            dispatch(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
            $order = CreateSalesOrderJob::dispatchNow($invoice->fresh(), $this);
            dispatch(new HoldItemQtyJob($invoice, $order));
            DB::commit();
            $path = CreateOrderPdfSnapshotJob::dispatchNow($invoice);
            event(new OrderCreatedEvent($invoice, $path));
            return redirect('/web/orders');
        } catch (QueryException $queryException) {
            DB::rollBack();
            throw $queryException;
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        }
    }


    private function validateQuantities($items = [])
    {
        foreach ($items as $item) {
            $dbItem = Item::find($item['id']);
            if (!$dbItem->is_service && !$dbItem->is_expense && !$dbItem->is_kit) {
                if ((int)$dbItem->available_qty < (int)$item['quantity']) {
                    throw ValidationException::withMessages(['item_available_quantity' => "you can't sale this items , qty not"]);
                }
            }
        }

    }

}
