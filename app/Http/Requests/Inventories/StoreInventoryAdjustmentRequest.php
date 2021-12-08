<?php

namespace App\Http\Requests\Inventories;

use App\Jobs\Accounting\Inventory\Adjustment\StoreInventoryAdjustmentTransactionsJob;
use App\Jobs\Inventory\Adjustments\Items\StoreInventoryAdjustmentItemsJob;
use App\Jobs\Invoices\Balance\UpdateInvoiceBalancesByInvoiceItemsJob;
use App\Jobs\Invoices\Number\UpdateInvoiceNumberJob;
use App\Models\Invoice;
use App\Models\Manager;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreInventoryAdjustmentRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*.id' => 'required|organization_exists:App\Models\Item,id',
            'items.*.qty' => 'required|quantity|min:0',
            'items.*.serials' => 'nullable|array',
            'items.*.serials.*' => 'nullable',
            'created_at' => "nullable"
        ];
    }

    /**
     * @throws Exception
     */
    public function store()
    {
        DB::beginTransaction();

        try {

            $user = User::where([
                ['user_slug', 'beginning-inventory'],
                ['is_system_user', true]
            ])->first();
            $authUser = $this->userLogged();
            $invoice = Invoice::create(
                [
                    'invoice_type' => 'inventory_adjustment',
                    'notes' => "",
                    'creator_id' => $authUser->id,
                    'organization_id' => $authUser->organization_id,
                    'branch_id' => $authUser->getOriginal("branch_id"),
                    'department_id' => $authUser->getOriginal("department_id"),
                    'user_id' => $user->id,
                    'managed_by_id' => $authUser->id,
                    'created_at' => $this->getCreatedAt(),
                    'updated_at' => $this->getCreatedAt()
                ]
            );
            dispatch_sync(new UpdateInvoiceNumberJob($invoice, 'IA'));
            dispatch_sync(new StoreInventoryAdjustmentItemsJob($invoice, (array)$this->input('items'), false, $this->getCreatedAt()));
            dispatch_sync(new UpdateInvoiceBalancesByInvoiceItemsJob($invoice));
            dispatch_sync(new StoreInventoryAdjustmentTransactionsJob($invoice->fresh(), $this->getCreatedAt()));
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function userLogged(): Manager
    {
        return Auth::user();
    }

    public function getCreatedAt(): Carbon
    {
        $createdAt = $this->input('created_at');
        if ($createdAt)
            return Carbon::parse($createdAt);
        return Carbon::now();
    }

    public function getItems()
    {
        return $this->input('items', []);
    }
}
