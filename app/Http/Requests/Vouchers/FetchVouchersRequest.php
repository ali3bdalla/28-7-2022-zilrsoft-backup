<?php

namespace App\Http\Requests\Vouchers;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class FetchVouchersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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

    public function getData()
    {

        $query = Voucher::where('id', '!=', 0);
        if ($this->has('startDate') && $this->filled('startDate') && $this->has('endDate') &&
            $this->filled('endDate')) {
            $startDate = Carbon::parse($this->input("startDate"));
            $endDate = Carbon::parse($this->input("endDate"));

            if ($startDate === $endDate) {
                $query = $query->whereDate('created_at', $startDate);
            } else {
                $query = $query->whereBetween('created_at', [
                    $startDate,
                    $endDate,
                ]);
            }

        }

        if ($this->has('creators') && $this->filled('creators')) {
            $query = $query->whereIn('creator_id', $this->input("creators"));
        }

        if ($this->has('identities') && $this->filled('identities')) {
            $query = $query->whereIn('user_id', $this->input("identities"));
        }

        if ($this->has('id') && $this->filled('id')) {
            $query = $query->where('id', $this->input("id"));
        }

        if ($this->has('amount') && $this->filled('amount')) {
            $amount = explode("-", $this->amount);
            if (count($amount) >= 2) {
                $startAmount = $amount[0];
                $endAmount = $amount[1];
            } else {
                $startAmount = $this->amount;
                $endAmount = $this->amount;
            }
            $query = $query->whereBetween('amount', [$startAmount, $endAmount]);
        }

        if ($this->has('payment_type') && $this->filled('payment_type')) {
            if (in_array($this->input("payment_type"), ['receipt', 'payment'])) {
                $query = $query->where('payment_type', $this->input("payment_type"));
            }

        }

        if ($this->has('orderBy') && $this->filled('orderBy') && $this->has('orderType') && $this->filled('orderType')) {
            $query = $query->orderBy($this->orderBy, $this->orderType);
        } else {
            $query = $query->orderByDesc("id");
        }

        $query = $query->with('user', 'invoice', 'account', 'creator');

        if ($this->has('itemsPerPage') && $this->filled('itemsPerPage') && intval($this->input("itemsPerPage")
            ) >= 1 && intval($this->input('itemsPerPage')) <= 100) {
            return $query->paginate(intval($this->input('itemsPerPage')));
        } else {
            return $query->paginate(20);

        }

    }
}
