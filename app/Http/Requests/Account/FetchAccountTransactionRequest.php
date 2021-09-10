<?php

namespace App\Http\Requests\Account;

use App\ValueObjects\MoneyValueObject;
use Illuminate\Foundation\Http\FormRequest;

class FetchAccountTransactionRequest extends FormRequest
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
            //
            'amount' => ['nullable', 'numeric'],
            'startDate' => ['nullable', 'string'],
            'endDate' => ['nullable', 'string'],
            'invoice_id' => ['nullable', 'integer'],
            'user_id' => ['nullable', 'integer'],
            'item_id' => ['nullable', 'integer'],
            'sort_by' => ['nullable', 'string'],
            'order_type' => ['nullable', 'string', 'in:asc,desc'],
        ];
    }

    public function getSortColumn()
    {
        return $this->input('sort_by');
    }

    public function getSortDirection()
    {
        return $this->input('order_type');
    }

    public function getAmountMoney(): MoneyValueObject
    {
        return new MoneyValueObject($this->input('amount'), 'SAR');
    }

    public function getStartAt()
    {
        return $this->input('startDate');
    }

    public function getEndAt()
    {
        return $this->input('endDate');
    }

    public function getInvoiceId()
    {
        return $this->input('invoice_id');
    }

    public function getUserId()
    {
        return $this->input('user_id');
    }

    public function getItemId()
    {
        return $this->input('item_id');
    }
}
