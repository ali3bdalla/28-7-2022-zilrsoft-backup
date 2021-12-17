<?php

namespace App\Http\Resources\Entity;

use App\Enums\AccountingTypeEnum;
use App\Enums\AccountSlugEnum;
use App\Repository\AccountRepositoryContract;
use App\ValueObjects\TransactionSearchValueObject;
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
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type,
            'container_id' => $this->resource->container_id,
            'account_id' => $this->resource->account_id,
            'amount' => $this->resource->amount,
            'created_at' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            'account_name' => $this->resource->account_name,
            'invoice_id' => $this->resource->invoice_id,
            'user_id' => $this->resource->user_id,
            'item_id' => $this->resource->item_id,
            "balance" => $this->key === 0 ? $this->getFirstItemBalance() : 0,
            "debit_amount" => $this->resource->isDebit() ? $this->resource->amount : 0,
            "credit_amount" => $this->resource->isCredit() ? $this->resource->amount : 0,
            'invoice_number' => $this->resource->invoice ? $this->resource->invoice->invoice_number : "",
            'invoice_url' => $this->resource->invoice ? $this->resource->invoice->appShowUrl() : "",
        ];
    }

    private function getFirstItemBalance(): float
    {
        $userId = $this->resource->account->slug->equals(AccountSlugEnum::vendors()) || $this->resource->account->slug->equals(AccountSlugEnum::clients()) ? $this->resource->user_id : null;
        $repo = app(AccountRepositoryContract::class);
        $transactionSearchValueObject = new TransactionSearchValueObject(null, $userId, null, null,null, $this->resource->created_at);
        return $repo->getAccountBalance($this->resource->account, $transactionSearchValueObject);
    }

}
