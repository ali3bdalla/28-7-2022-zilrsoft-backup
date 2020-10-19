<?php

namespace App\Models;

use App\Events\Models\Transaction\TransactionCreated;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed account
 * @property mixed amount
 * @property mixed created_at
 * @property mixed type
 */
class Transaction extends BaseModel
{
    use  SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
    ];


      /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TransactionCreated::class,
    ];


  

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }


    public function getAccountNameAttribute()
    {
        $account = $this->account;
        if (($account->slug == 'vendors' || $account->slug == 'clients') && $this->user_id) {
            $user = User::find($this->user_id);
            if ($user) {
                return $user->name;
            }

        }


        if (($account->slug == 'stock') && $this->item_id) {
            $item = Item::find($this->item_id);
            if ($item) {
                return $item->locale_name;
            }

        }


        return $this->account->locale_name;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
	
	
	public function item()
	{
		return $this->belongsTo(Item::class, 'item_id');
	}

	
	
	public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function container()
    {
        return $this->belongsTo(TransactionsContainer::class, 'container_id');
    }
	
	

}
