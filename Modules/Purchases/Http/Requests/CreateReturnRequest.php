<?php

namespace Modules\Purchases\Http\Requests;

use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\TransactionsContainer;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Accounting\Jobs\CreateReturnPurchasesEntityTransactionsJob;
use Modules\Purchases\Jobs\ChangeInvoiceUpdatedAndDeletedJob;
use Modules\Purchases\Jobs\CreateReturnPurchasesItemsJob;
use Modules\Purchases\Jobs\EnsureReturnPurchasesDataAreCorrectJob;
use Modules\Purchases\Jobs\UpdateInvoiceTotalsJob;


class CreateReturnRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'items' => 'required|array',
            'items.*.id' => 'integer|required|exists:invoice_items,id',
            'items.*.returned_qty' => 'required',
            'methods.*.id' => 'integer|required|exists:accounts,id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('edit purchase');
    }


    public function store(Invoice $purchase)
    {

        DB::beginTransaction();
        try {
            $this->validateInvoiceType($purchase);
            $this->validateItemsBelongsTo($purchase);
            $returnedItems = $this->getReturnedItems();
            $authUser = auth()->user();
            $invoice = Invoice::create([
                'invoice_type' => 'return_purchase',
                'notes' => $this->has('notes') ? $this->input('notes') : "",
                'creator_id' => $authUser->id,
                'organization_id' => $purchase->organization_id,
                'branch_id' => $purchase->branch_id,
                'department_id' => $purchase->department_id,
                'parent_invoice_id' => $purchase->id,
                'is_deleted' => false
            ]);
            $Purchase = $invoice->purchase()->create([
                'vendor_id' => $purchase->purchase->vendor_id,
                'receiver_id' => $purchase->purchase->receiver_id,
                'organization_id' => $purchase->organization_id,
                'invoice_type' => 'return_purchase',
                "prefix" => "RPU-"
            ]);

            $transactionContainer = new TransactionsContainer(
                [
                    'creator_id' => $this->user()->id,
                    'organization_id' => $this->user()->organization_id,
                    'invoice_id' => $invoice->id,
                    'amount' => 0,
                    'description' => 'invoice'
                ]
            );
            
            $transactionContainer->save();
            // dd(1);
            dispatch(new CreateReturnPurchasesItemsJob($transactionContainer, $invoice, $returnedItems, $purchase));
            dispatch(new UpdateInvoiceTotalsJob($invoice));
            dispatch(new CreateReturnPurchasesEntityTransactionsJob($transactionContainer, $invoice, $this->input("methods")));
            dispatch(new EnsureReturnPurchasesDataAreCorrectJob($invoice));
            dispatch(new ChangeInvoiceUpdatedAndDeletedJob($invoice));
            DB::commit();
            return response($invoice, 200);
        } catch (QueryException $queryException) {
            DB::rollBack();
            throw $queryException;
        } catch (ValidationException $validationException) {
            DB::rollBack();
            throw $validationException;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;

        }
    }


    private function validateInvoiceType(Invoice $invoice)
    {
        if ($invoice->purchase == null || !$invoice->purchase instanceof Purchase || $invoice->invoice_type != 'purchase' || $invoice->purchase->invoice_type != 'purchase') {
            $error = ValidationException::withMessages([
                "invoice" => ['must be purchase invoice'],
            ]);
            throw  $error;
        }

    }

    private function validateItemsBelongsTo(Invoice $sale)
    {
        $items = $sale->items()->pluck('id')->toArray();
        foreach ($this->input('items') as $item) {
            if (!in_array($item['id'], $items)) {
                $error = ValidationException::withMessages([
                    "invoice" => ['all items must belongs to current invoice'],
                ]);
                throw  $error;

            }
        }
    }

    private function getReturnedItems()
    {
        $items = [];
        foreach ($this->input('items') as $item) {
            if ((int)$item['returned_qty'] >= 1) {
                $items[] = $item;
            }
        }

        if (empty($items)) {
            $error = ValidationException::withMessages([
                "invoice" => ['retruned items must be at lest one item'],
            ]);
            throw  $error;
        }
        return $items;
    }
}
