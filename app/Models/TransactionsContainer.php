<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * @property mixed id
     */
    class TransactionsContainer extends BaseModel
	{
		protected $guarded = [];
		use SoftDeletes;

		


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
	}
