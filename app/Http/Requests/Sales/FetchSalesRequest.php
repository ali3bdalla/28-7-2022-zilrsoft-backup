<?php

namespace App\Http\Requests\Sales;

use App\Enums\InvoiceTypeEnum;
use App\Models\Invoice;
use App\Scopes\DraftScope;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Enum\Laravel\Rules\EnumRule;

class FetchSalesRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'invoice_type' => ['nullable', new EnumRule(InvoiceTypeEnum::class)],
            'is_draft' => ['nullable'],
            'startDate' => ['nullable', 'string'],
            'endDate' => ['nullable', 'string'],
            'creators' => ['nullable', 'array'],
            'creators.*' => ['required', 'integer'],
            'salesmen' => ['nullable', 'array'],
            'salesmen.*' => ['required', 'integer'],
            'clients' => ['nullable', 'array'],
            'clients.*' => ['required', 'integer'],
            'aliceName' => ['nullable', 'string'],
            'net' => ['nullable', 'numeric'],
            'total' => ['nullable', 'numeric'],
            'discount' => ['nullable', 'numeric'],
            'subtotal' => ['nullable', 'numeric'],
            'tax' => ['nullable', 'numeric'],
            'title' => ['nullable', 'string'],
        ];
    }

    public function getInvoiceType(): InvoiceTypeEnum
    {
        return InvoiceTypeEnum::from($this->input('invoice_type', 'sale'));
    }

    public function getIsDraft()
    {
        return $this->input('is_draft');
    }

    public function getStartDate()
    {
        return $this->input('startDate');
    }

    public function getEndDate()
    {
        return $this->input('endDate');
    }

    public function getCreators()
    {
        return $this->input('creators');
    }

    public function getSalesmen()
    {
        return $this->input('salesmen');
    }

    public function getClients()
    {
        return $this->input('clients');
    }

    public function getAliceName()
    {
        return $this->input('aliceName');
    }

    public function getNet()
    {
        return $this->input('net');
    }

    public function getTotal()
    {
        return $this->input('total');
    }

    public function getDiscount()
    {
        return $this->input('discount');
    }

    public function getSubtotal()
    {
        return $this->input('subtotal');
    }

    public function getTax()
    {
        return $this->input('tax');
    }

    public function getTitle()
    {
        return $this->input('title');
    }

    public function getData()
    {
        $query = Invoice::whereIn('invoice_type', ['sale', 'return_sale']);
        if ($this->has('is_draft') && $this->filled('is_draft')) {
            $isDraft = (bool) $this->input('is_draft');
            if ($isDraft) {
                $query = $query->withoutGlobalScopes([DraftScope::class])->where([
                    ['is_draft', $isDraft],
                    ['is_online', false],
                ]);
            }
        }

        $query = $query->with(
            [
                'creator', 'items', 'user', 'manager',
            ]
        );

        if (
            $this->has('startDate') && $this->filled('startDate') && $this->has('endDate')
            && $this->filled('endDate')
        ) {
            $_startDate = Carbon::parse($this->input('startDate'));
            $_endDate = Carbon::parse($this->input('endDate'));

            if ($_endDate === $_startDate) {
                $query = $query->whereDate('created_at', $_startDate);
            } else {
                $query = $query->whereBetween(
                    'created_at',
                    [
                        $_startDate,
                        $_endDate,
                    ]
                );
            }
        } else {
            if (!$this->user()->can('manage branches') && !$this->filled('title') && !$this->filled('aliceName') && null != auth()->user()->accounts_closed_at) {
                $query = $query->where('created_at', '>=', Carbon::parse(auth()->user()->accounts_closed_at));
            }
        }

        if ($this->has('creators') && $this->filled('creators')) {
            $query = $query->whereIn('creator_id', $this->input('creators'));
        }

        if ($this->has('departments') && $this->filled('departments')) {
            $query = $query->whereIn('department_id', $this->input('departments'));
        }

        if ($this->has('clients') && $this->filled('clients')) {
            $query = $query->whereIn('user_id', $this->filled('clients'));
        }

        if ($this->has('salesmen') && $this->filled('salesmen')) {
            $query = $query->where('managed_by_id', $this->input('salesmen'));
        }

        if ($this->has('aliceName') && $this->filled('aliceName')) {
            $query = $query->where('user_alice_name', 'ILIKE', '%'.$this->input('aliceName').'%');
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

        if ($this->has('current_status') && $this->filled('current_status')) {
            if (in_array($this->input('current_status'), ['credit', 'paid'])) {
                $query = $query->where('current_status', $this->input('current_status'));
            }
        }

        if ($this->has('invoice_type') && $this->filled('invoice_type')) {
            if (in_array($this->input('invoice_type'), ['sale', 'return_sale'])) {
                $query = $query->where('invoice_type', $this->input('invoice_type'));
            }
        }

        if ($this->has('orderBy') && $this->filled('orderBy') && $this->has('orderType') && $this->filled('orderType')) {
            $query = $query->orderBy($this->orderBy, $this->orderType);
        }

        $query = $query->withCount(
            [
                'items AS invoice_cost' => function ($query) {
                    $query->select(DB::raw('SUM(cost * qty) as invoice_cost'));
                },
            ]
        );

        if ($this->has('itemsPerPage') && $this->filled('itemsPerPage') && intval($this->input('itemsPerPage')) >= 1) {
            $result = $query->paginate(intval($this->input('itemsPerPage')));
        } else {
            $result = $query->paginate(50);
        }

        return $result;
    }
}
