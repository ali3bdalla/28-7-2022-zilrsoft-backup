<?php

namespace App\Http\Requests\Sales;

use App\Jobs\Accounting\Sale\StoreSaleTransactionsJob;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Items\Serial\ValidateItemSerialJob;
use App\Jobs\Sales\Items\StoreSaleItemsJob;
use App\Models\Invoice;
use App\Models\Item;
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
            "items.*.id" => "required|integer|exists:items,id",
            "items.*.price" => "price|priceAndDiscount",
            "items.*.purchase_price" => "price",
            "items.*.discount" => "priceAndDiscount",
            "items.*.qty" => "required|integer|min:1|salesItemQty",
            "items.*.expense_vendor_id" => "itemVendorExpenseId",
            'items.*.serials' => 'array|newInvoiceItemSerials',
            'items.*.serials.*' => 'required|exists:item_serials,serial',
            "client_id" => "required|integer|exists:users,id",
            "salesman_id" => "required|integer|exists:managers,id",
            'methods.*.id' => 'required|integer|exists:accounts,id',
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
            $this->validateSerials();
            $this->validateQuantities($this->input('items'));

            $authUser = auth()->user();
//            dispatch(new AddExpensesPurchasesJob($this->input('items')));
            $invoice = Invoice::create([
                'invoice_type' => 'sale',
                'notes' => $this->has('notes') ? $this->input('notes') : "",
                'creator_id' => $authUser->id,
                'organization_id' => $authUser->organization_id,
                'branch_id' => $authUser->branch_id,
                'department_id' => $authUser->department_id,
                'user_id' => $this->client_id,
                'managed_by_id' => $this->salesman_id,
            ]);
            $invoice->sale()->create([
                'salesman_id' => $authUser->id,
                'client_id' => $this->input('client_id'),
                'organization_id' => $authUser->organization_id,
                'invoice_type' => 'sale',
                'alice_name' => $this->input('alice_name'),
                "prefix" => "SAI-"
            ]);
            dispatch(new UpdateInvoiceNumberJob($invoice, 'SAI-'));
            dispatch(new StoreSaleItemsJob($invoice, (array)$this->input('items')));
            dispatch(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
//            dispatch(new StoreSalePaymentsJob($invoice));
            dispatch(new StoreSaleTransactionsJob($invoice));
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


    private function validateQuantities($items = [])
    {
        foreach ($items as $item) {
            $dbItem = Item::find($item['id']);
            if (!$dbItem->is_service) {
                if ((int)$dbItem->available_qty < (int)$item['qty']) {
                    throw ValidationException::withMessages(['item_available_quantity' => "you can't sale this items , qty not"]);
                }
            }


        }

    }

}
