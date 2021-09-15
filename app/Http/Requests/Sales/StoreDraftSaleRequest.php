<?php

namespace App\Http\Requests\Sales;

use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Jobs\Items\Serial\ValidateItemSerialJob;
use App\Jobs\Sales\Items\StoreSaleItemsJob;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\ItemSerials;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreDraftSaleRequest extends FormRequest
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
            "items.*.qty" => "required|quantity|min:1",
            'items.*.items.*.id' => 'required|organization_exists:App\Models\Item,id',
            'items.*.items.*.qty' => 'required|quantity|salesKitItemValidator',
            "client_id" => "required|integer|exists:users,id",
            "salesman_id" => "required|integer|exists:managers,id",

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
            //				$this->validateSerials();
            $this->validateKits();
            //				$this->validateQuantities($this->input('items'));
            $authUser = auth()->user();

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
                    'is_draft' => true
                ]
            );
            dispatch_sync(new UpdateInvoiceNumberJob($invoice, 'QA-'));
            dispatch_sync(new StoreSaleItemsJob($invoice, (array)$this->input('items'), true));
            dispatch_sync(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));

            DB::commit();
            return $invoice->load('items');
        } catch (QueryException $queryException) {
            DB::rollBack();
            throw $queryException;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
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

    private function validateSerials()
    {
        foreach ($this->input('items') as $item) {
            $dbItem = Item::find($item['id']);
            if ($dbItem->is_need_serial) {
                if (count($item['serials']) != $item['qty']) {
                    throw ValidationException::withMessages(['item_serial' => 'serials count don\'t  match qty']);
                }
                foreach ($item['serials'] as $serial) {
                    dispatch_sync(new ValidateItemSerialJob($dbItem, $serial, ['sold', 'return_purchase']));
                }
            }
        }
    }

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
}
