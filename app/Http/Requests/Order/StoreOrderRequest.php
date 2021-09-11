<?php

namespace App\Http\Requests\Order;

use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Order\HoldItemQtyJob;
use App\Jobs\Order\NotifyCustomerByNewOrderJob;
use App\Jobs\Sales\Items\StoreSaleItemsJob;
use App\Jobs\Sales\Order\CreateSalesOrderJob;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Manager;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Rules\ExistsRule;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
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
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws ValidationException
     */
    public function store()
    {
        DB::beginTransaction();
        try {
            $this->validateQuantities();

            $this->validateQuantities($this->input('items'));
            $authUser = Manager::first();
            $authClient = $this->loggedUser();
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
            $invoice->sale()->create(
                [
                    'salesman_id' => $authUser->id,
                    'client_id' => $authClient->id,
                    'organization_id' => $authUser->organization_id,
                    'invoice_type' => 'sale',
                    'alice_name' => null,
                    "prefix" => "O",
                    'is_draft' => true
                ]
            );
            dispatch_sync(new UpdateInvoiceNumberJob($invoice, 'ONLINE'));
            dispatch_sync(new StoreSaleItemsJob($invoice, (array)$this->input('items'), true, $authUser, true));
            dispatch_sync(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
            $order = CreateSalesOrderJob::dispatchSync($invoice->fresh(), $this);
            dispatch_sync(new HoldItemQtyJob($invoice, $order));
            dispatch_sync(new NotifyCustomerByNewOrderJob($order, "", $invoice));
            DB::commit();
            if ($this->acceptsJson())
                return $invoice;
            return redirect('/web/profile');
        } catch (QueryException $queryException) {
            DB::rollBack();
            throw $queryException;
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateQuantities($items = [])
    {
        foreach ($items as $item) {
            $dbItem = Item::find($item['id']);
            if (!$dbItem->is_service && !$dbItem->is_expense && !$dbItem->is_kit) {
                if ((float)$dbItem->available_qty < (float)$item['quantity']) {
                    throw ValidationException::withMessages(['item_available_quantity' => "you can't sale this items , qty not"]);
                }
            }
        }
    }

    public function loggedUser(): User
    {
        return Auth::guard("client")->user();
    }
}
