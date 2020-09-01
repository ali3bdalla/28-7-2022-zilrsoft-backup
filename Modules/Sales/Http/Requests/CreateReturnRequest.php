<?php

namespace Modules\Sales\Http\Requests;

use App\Invoice;
use App\SaleInvoice;
use App\TransactionsContainer;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Accounting\Jobs\CreateReturnSalesEntityTransactionsJob;
use Modules\Sales\Jobs\ChangeInvoiceUpdatedAndDeletedJob;
use Modules\Sales\Jobs\CreateReturnSalesItemsJob;
use Modules\Sales\Jobs\EnsureReturnSalesDataAreCorrectJob;
use Modules\Sales\Jobs\UpdateInvoiceTotalsJob;

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
            'items.*.serials' => 'nullable|array',
            'items.*.serials.*' => 'required|string|exists:item_serials,serial',
            "methods" => 'nullable|array',
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
        return $this->user()->can('edit sale');
    }


    public function store(Invoice $sale)
    {

        DB::beginTransaction();
        try {
            $this->validateInvoiceType($sale);
            $this->validateItemsBelongsTo($sale);
            $returnedItems = $this->getReturnedItems();
            $authUser = auth()->user();
            $invoice = Invoice::create([
                'invoice_type' => 'r_sale',
                'notes' => $this->has('notes') ? $this->input('notes') : "",
                'creator_id' => $authUser->id,
                'organization_id' => $sale->organization_id,
                'branch_id' => $sale->branch_id,
                'department_id' => $sale->department_id,
                'parent_invoice_id' => $sale->id,
                'is_deleted' => false
            ]);
            $salesInvoice = $invoice->sale()->create([
                'salesman_id' => $sale->sale->salesman_id,
                'client_id' => $sale->sale->client_id,
                'organization_id' => $sale->organization_id,
                'invoice_type' => 'r_sale',
                'alice_name' => $sale->sale->alice_name,
                "prefix" => "RSI-"
            ]);

            $transactionContaniner = new TransactionsContainer(
                [
                    'creator_id' => $this->user()->id,
                    'organization_id' => $this->user()->organization_id,
                    'invoice_id' => $invoice->id,
                    'amount' => 0,
                    'description' => 'invoice'
                ]
            );
            $transactionContaniner->save();
            dispatch(new CreateReturnSalesItemsJob($transactionContaniner, $invoice, $returnedItems, $sale));
            dispatch(new UpdateInvoiceTotalsJob($invoice));
            dispatch(new CreateReturnSalesEntityTransactionsJob($transactionContaniner, $invoice, $this->input("methods")));
            dispatch(new EnsureReturnSalesDataAreCorrectJob($invoice));
            dispatch(new ChangeInvoiceUpdatedAndDeletedJob($invoice));
            DB::commit();
            return $invoice;
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
        if ($invoice->sale == null || !$invoice->sale instanceof SaleInvoice || $invoice->invoice_type != 'sale' || $invoice->sale->invoice_type != 'sale') {
            $error = ValidationException::withMessages([
                "invoice" => ['must be sales invoice'],
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
