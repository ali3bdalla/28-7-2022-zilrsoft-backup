<?php
	
	namespace App;
	
	use App\Scopes\CountryScope;
	use Illuminate\Database\Eloquent\Model;
	
	
	class CountryBank extends Model
	{
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new CountryScope(auth()->user()->organization->country_id));
			}
		}
		
		public function country()
		{
			return $this->belongsTo(Country::class,'country_id');
		}
		
		public function accounts()
		{
			return $this->hasMany(GatewayAccounts::class,'bank_id');
		}
		
		public function getNameAttribute($value)
		{
			if (app()->isLocale('ar')){
				return $this->ar_name;
			}
			
			return $value;
		}
		//
	}