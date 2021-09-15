<?php

namespace App\Http\Requests\Purchases;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class FetchPurchasesRequest extends FormRequest
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
        ];
    }

    public function getData()
    {
        if ($this->has('isPending') && $this->filled('isPending') && 1 == $this->input('isPending')) {
            $query = Invoice::whereIn('invoice_type', ['return_purchase', 'purchase'])->where('is_draft', true);
        } else {
            $query = Invoice::whereIn('invoice_type', ['return_purchase', 'purchase'])->where('is_draft', false);
        }

        if (
            $this->has('startDate') && $this->filled('startDate') && $this->has('endDate')
            && $this->filled('endDate')
        ) {
            $_startDate = Carbon::parse($this->input('startDate'))->toDateString();
            $_endDate = Carbon::parse($this->input('endDate'))->toDateString();
            if ($_endDate === $_startDate) {
                $query = $query->whereDate('created_at', $_startDate);
            } else {
                $query = $query->whereBetween('created_at', [
                    $_startDate->toDateString(),
                    $_endDate->toDateString(),
                ]);
            }
        }

        if ($this->has('creators') && $this->filled('creators')) {
            $query = $query->whereIn('creator_id', $this->input('creators'));
        }

        if ($this->has('departments') && $this->filled('departments')) {
            $query = $query->whereIn('department_id', $this->input('departments'));
        }

        if ($this->has('vendors') && $this->filled('vendors')) {
            $query = $query->whereIn('managed_by_id', $this->input('vendors'));
        }

        if ($this->has('id') && $this->filled('id')) {
            $query = $query->where('id', $this->id);
        }

        if ($this->has('title') && $this->filled('title')) {
            $arr = explode('-', $this->input('title'));
            if (count($arr) >= 2) {
                $number = $arr[1];
            } else {
                $number = $this->input('title');
            }

            $query = $query->where('id', (float) $number)->orWhere('invoice_number', 'iLIKE', $number);
        }

        if ($this->has('net') && $this->filled('net')) {
            $amount = explode('-', $this->net);
            if (count($amount) >= 2) {
                $startAmount = $amount[0];
                $endAmount = $amount[1];
            } else {
                $startAmount = $this->net;
                $endAmount = $this->net;
            }
            $query = $query->whereBetween('net', [$startAmount, $endAmount]);
        }
        if ($this->has('tax') && $this->filled('tax')) {
            $amount = explode('-', $this->tax);
            if (count($amount) >= 2) {
                $startAmount = $amount[0];
                $endAmount = $amount[1];
            } else {
                $startAmount = $this->tax;
                $endAmount = $this->tax;
            }
            $query = $query->whereBetween('tax', [$startAmount, $endAmount]);
        }
        if ($this->has('total') && $this->filled('total')) {
            $amount = explode('-', $this->total);
            if (count($amount) >= 2) {
                $startAmount = $amount[0];
                $endAmount = $amount[1];
            } else {
                $startAmount = $this->total;
                $endAmount = $this->total;
            }
            $query = $query->whereBetween('total', [$startAmount, $endAmount]);
        }
        if ($this->has('discount') && $this->filled('discount')) {
            $amount = explode('-', $this->discount);
            if (count($amount) >= 2) {
                $startAmount = $amount[0];
                $endAmount = $amount[1];
            } else {
                $startAmount = $this->discount;
                $endAmount = $this->discount;
            }
            $query = $query->whereBetween('discount', [$startAmount, $endAmount]);
        }
        if ($this->has('subtotal') && $this->filled('subtotal')) {
            $amount = explode('-', $this->subtotal);
            if (count($amount) >= 2) {
                $startAmount = $amount[0];
                $endAmount = $amount[1];
            } else {
                $startAmount = $this->subtotal;
                $endAmount = $this->subtotal;
            }
            $query = $query->whereBetween('subtotal', [$startAmount, $endAmount]);
        }

        if ($this->has('orderBy') && $this->filled('orderBy') && $this->has('orderType') && $this->filled('orderType')) {
            $query = $query->orderBy($this->orderBy, $this->orderType);
        } else {
            $query = $query->orderByDesc('id');
        }

        $query = $query->with('creator', 'items', 'manager', 'user');
        if ($this->has('itemsPerPage') && $this->filled('itemsPerPage') && intval(
            $this->input('itemsPerPage')
        ) >= 1 && intval($this->input('itemsPerPage')) <= 100) {
            return $query->paginate(intval($this->input('itemsPerPage')));
        }

        return $query->paginate(20);
    }
}
