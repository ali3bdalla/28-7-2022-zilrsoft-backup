<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	class Sale extends BaseModel
	{
		//
		use SoftDeletes;
		
		protected $guarded = [];
		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class, 'invoice_id');
		}
		
		public function client()
		{
			return $this->belongsTo(User::class, 'client_id')->withoutGlobalScopes(['draft','manager']);
		}
		
		public function salesman()
		{
			return $this->belongsTo(Manager::class, 'salesman_id');
		}
		
		public function serials()
		{
			return $this->hasMany(ItemSerials::class, 'sale_invoice_id', 'invoice_id');
		}
		
		
		
		
	}
