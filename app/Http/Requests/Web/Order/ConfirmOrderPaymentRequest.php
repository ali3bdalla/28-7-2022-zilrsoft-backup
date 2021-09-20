<?php

namespace App\Http\Requests\Web\Order;

use App\Events\Order\ClientUpdateOrderPaymentEvent;
use App\Models\Order;
use App\Package\Whatsapp;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ConfirmOrderPaymentRequest extends FormRequest
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
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'sender_account_id' => 'required|integer|exists:user_gateways,id',
            'receiver_bank_id' => 'required|integer|exists:banks,id',
        ];
    }
    public function getFirstName()
    {
        return $this->input('first_name');
    }
    public function getLastName()
    {
        return $this->input('last_name');
    }
    public function getSendAccountId()
    {
        return $this->input('sender_account_id');
    }
    public function getReceiverBankId()
    {
        return $this->input('receiver_bank_id');
    }
}
