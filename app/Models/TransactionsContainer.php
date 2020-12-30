<?php
	
	namespace App\Models;

use App\Models\Traits\AccountingPeriodTrait;
use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * @property mixed id
     */
    class TransactionsContainer extends BaseModel
	{
		protected $guarded = [];
		use SoftDeletes;
		use AccountingPeriodTrait;
	
	    public function getTotalCreditAmountAttribute()
	    {
		    return $this->transactions()->where('type','credit')->sum('amount');
		}
	
	
	    public function getTotalDebitAmountAttribute()
	    {
		    return $this->transactions()->where('type','debit')->sum('amount');
	    }


		// new relationship
        public function entities()
        {
            return $this->hasMany(Transaction::class,'container_id');
        }


        // old relationship
		public function transactions()
		{
			return $this->hasMany(Transaction::class,'container_id');
		}
		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class,'invoice_id');
		}
	
	    public function creator()
	    {
		    return $this->belongsTo(Manager::class,'creator_id');
	    }
	}
