<?php
	
	namespace App;
	
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class GatewayAccounts extends Model
	{
		
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
		public function gateway()
		{
			return $this->belongsTo(Gateway::class,'gateway_id');
		}
		
		public function bank()
		{
			return $this->belongsTo(CountryBank::class,'bank_id');
		}
		
		public function accountable()
		{
			return $this->morphTo();
		}
		//
	}
