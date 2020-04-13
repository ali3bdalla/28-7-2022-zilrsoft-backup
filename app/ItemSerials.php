<?php
	
	namespace App;
	
	use App\Core\CoreItemSerials;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	
	class ItemSerials extends Model
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
			
		
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
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
