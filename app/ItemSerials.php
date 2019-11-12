<?php
	
	namespace App;
	
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class ItemSerials extends Model
	{
		
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
		//

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
