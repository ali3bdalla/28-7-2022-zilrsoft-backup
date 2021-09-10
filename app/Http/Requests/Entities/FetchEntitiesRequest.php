<?php

namespace App\Http\Requests\Entities;

use App\Http\Resources\Entity\TransactionCollection;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed orderType
 * @property mixed orderBy
 */
class FetchEntitiesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // return $this->user()->can('view sale');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function getData(Account $account)
    {

        $query = $account->transactions();
        if (
            $this->has('startDate') && $this->filled('startDate') && $this->has('endDate') &&
            $this->filled('endDate')
        ) {
            $startDate = Carbon::parse($this->input("startDate"))->toDateString();
            $endDate = Carbon::parse($this->input("endDate"))->toDateString();

            if ($endDate === $startDate) {
                $query = $query->whereDate('created_at', $startDate);
            } else {
                $query = $query->whereBetween(
                    'created_at',
                    [
                        $startDate,
                        $endDate,
                    ]
                );
            }
        }


        if ($this->has('invoice_id') && $this->filled('invoice_id')) {
            $query = $query->where('invoice_id', $this->input("invoice_id"));
        }


        if ($this->has('user_id') && $this->filled('user_id')) {
            $query = $query->where('user_id', $this->input("user_id"));
        }


        if ($this->has('item_id') && $this->filled('item_id')) {
            $query = $query->where('item_id', $this->input("item_id"));
        }


        if ($this->has('amount') && $this->filled('amount')) {
            $amount = explode("-", $this->input('amount'));
            if (count($amount) >= 2) {
                $startAmount = $amount[0];
                $endAmount = $amount[1];
            } else {
                $startAmount = $this->input('amount');
                $endAmount = $this->input('amount');
            }
            $query = $query->whereBetween('amount', [$startAmount, $endAmount]);
        }
        if ($this->has('order_by') && $this->filled('order_by') && $this->has('order_type') && $this->filled('order_type')) {
            $query = $query->orderBy($this->input("order_by"), $this->input('order_type'));
        } else {
            $query = $query->orderByDesc("created_at");
        }

        $query = $query->with('invoice', 'user', 'item');

        if ($this->has('itemsPerPage') && $this->filled('itemsPerPage') && (int)($this->input("itemsPerPage")) >= 1) {
            $result = $query->paginate(intval($this->input('itemsPerPage')));
        } else {
            $result = $query->paginate(100);
        }
        return new TransactionCollection($result);
    }
}
