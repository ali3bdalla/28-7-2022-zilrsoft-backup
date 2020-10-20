<?php
	
	namespace App\Models;
	

	use Illuminate\Database\Eloquent\Builder;

    /**
     * @property mixed organization_id
     * @property mixed current_status
     */
    class ItemSerials extends BaseModel
	{
		
		
		
		protected $guarded = [];
		protected $appends = [
			'status_description'
		];
		
	
		
		public function getStatusDescriptionAttribute()
		{
			return trans('pages/items.'.$this->current_status);
		}
		
		public function scopePurchase($query,$invoice_id)
		{
			return $query->where('purchase_id',$invoice_id);
		}
		
		public function scopeSale($query,$invoice_id)
		{
			return $query->where('sale_id',$invoice_id);
		}
		
		public function item()
		{
			return $this->belongsTo(Item::class,'item_id');
		}
		
		public function histories()
		{
			return $this->hasMany(SerialHistory::class,'serial_id');
		}


		// public function scopeInvoice($query,$invoiceId)
		// {
		// 	$serials = $this->histories()->where('invoice_id',$invoiceId)->pluck('serial_id')->toArray();
		// 	dd($serials);
		// 	die();
		// 	return $query->whereIn('id',$serials);
		// }
	}
