<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @property integer receiver_id
 * @property float remaining_accounts_balance
 * @property mixed container
 * @property mixed transaction_type
 * @property integer creator_id
 * @property integer id
 * @property float amount
 * @property Account toAccount
 * @property Manager creator
 * @property Account fromAccount
 * @method static where(array $array)
 * @method static myDailyCloseAccounts()
 */
class ResellerClosingAccount extends BaseModel
{

    protected $guarded = [];


    protected $casts = [
      'amount' => "float"
    ];
    /**
     * @param $builder
     * @return mixed
     */
    public function scopeMyDailyCloseAccounts($builder)
    {
        return $builder->where(
            [
                ['creator_id', Auth::id()],
                ['transaction_type', "close_account"],
            ]
        )->orWhere(
            [
                ['receiver_id', Auth::id()],
            ]
        );
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'receiver_id');
    }

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Entry::class, 'container_id')->withoutGlobalScope("pending");
    }


    public function scopeToMe($query, Request $request)
    {
        return $query->withoutGlobalScopes(['draft', 'pending'])->where([['is_pending', true], ['transaction_type', 'transfer'], ['receiver_id', $request->user()->id]])->with('creator', 'receiver', 'fromAccount', 'toAccount');
    }
}
