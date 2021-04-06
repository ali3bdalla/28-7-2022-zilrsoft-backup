<?php

namespace App\Http\Resources\Entity;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

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
    public function toArray($request)
    {

        $array = parent::toArray($request);
        $array['debit_amount'] = ($this->type == 'debit' ? $this->amount : 0);
        $array['credit_amount'] = ($this->type == 'credit' ? $this->amount : 0);

        if($this->key == 0)
        {


            $date = Carbon::parse($this->resource->created_at)->toDate();
            $balanceDebitAmount = Transaction::where(
                [
                    ['account_id', $this->resource->account_id],
                    ['type', 'debit']
                ]

            )->where('created_at', '<', $date)->sum('amount');

            $balanceCreditAmount = Transaction::where(
                [
                    ['account_id', $this->resource->account_id],
                    ['type', 'credit']
                ]

            )->where('created_at', '<', $date)->sum('amount');


            if ($this->resource->account->type === 'debit') {
                $balance = $balanceDebitAmount - $balanceCreditAmount;
            } else {
                $balance = $balanceCreditAmount - $balanceDebitAmount;
            }

            $array['balance'] = $balance;
        }else {
            $array['balance'] = 0;
        }


        return $array;
    }


}
