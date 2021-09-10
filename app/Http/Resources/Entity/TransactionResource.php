<?php

namespace App\Http\Resources\Entity;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{

    /**
     * @var null
     */
    private $key;

    public function __construct($resource, $key = null)
    {
        parent::__construct($resource);
        $this->key = $key;
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
//        parent::toArray($request)
        $array = [
            'id' => $this->resource->id,
            'type' => $this->resource->type,
            'container_id' => $this->resource->container_id,
            'account_id' => $this->resource->account_id,
            'amount' => $this->resource->amount,
            'total_debit_amount' => $this->resource->total_debit_amount,
            'total_credit_amount' => $this->resource->total_credit_amount,
            'created_at' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            'account_name' => $this->resource->account_name,
            'invoice_id' => $this->resource->invoice_id,
            'user_id' => $this->resource->user_id,
            'item_id' => $this->resource->item_id,
            "balance" => 0
        ];
        $array['debit_amount'] = ($this->resource->isDebit() ? $this->resource->amount : 0);
        $array['credit_amount'] = ($this->resource->isCredit() ? $this->resource->amount : 0);
        if ($this->key == 0) {
            $date = Carbon::parse($this->resource->created_at)->toDate();
            $balanceDebitAmount = Transaction::where([['account_id', $this->resource->account_id], ['type', 'debit']])->where('created_at', '<', $date)
                ->sum('amount');
            $balanceCreditAmount = Transaction::where([['account_id', $this->resource->account_id], ['type', 'credit']])->where('created_at', '<', $date)
                ->sum('amount');
            if ($this->resource->account->isDebit()) {
                $array['balance'] = $balanceDebitAmount - $balanceCreditAmount;
            } else {
                $array['balance'] = $balanceCreditAmount - $balanceDebitAmount;
            }
        }
        return $array;
    }


}
