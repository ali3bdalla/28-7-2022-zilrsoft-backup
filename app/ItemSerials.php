<?php
	
	namespace App;
	
	use App\Core\CoreItemSerials;

	use Illuminate\Database\Eloquent\Builder;

	class ItemSerials extends BaseModel
	{
		
		use CoreItemSerials;
		
		
		protected $guarded = [];
		protected $appends = [
			'status_description'
		];
		
		//
		
		protected static function boot()
		{
			parent::boot();
			static::addGlobalScope('pendingSerialsScope',function (Builder $builder){
				$builder->where('is_pending',false);
			});
		}
		
		public function getStatusDescriptionAttribute()
		{
			return trans('pages/items.'.$this->current_status);
		}
		
		public function scopePurchase($query,$invoice_id)
		{
			return $query->where('purchase_invoice_id',$invoice_id);
		}
		
		public function scopeSale($query,$invoice_id)
		{
			return $query->where('sale_invoice_id',$invoice_id);
		}
		
		public function item()
		{
			return $this->belongsTo(Item::class,'item_id');
		}
		
		public function histories()
		{
			return $this->hasMany(SerialHistory::class,'serial_id');
		}
	}
