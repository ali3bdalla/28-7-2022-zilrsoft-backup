<?php
	
	namespace App;
	
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	class Expense extends Model
	{
		use SoftDeletes;
		protected $guarded = [];
		
		protected $appends = [
			'locale_name'
		];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
			
		}
		
		public function getLocaleNameAttribute()
		{
			if (app()->isLocale('ar'))
				return $this->ar_name;
			
			return $this->name;
			
		}
		
		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
		}
		
		public function invoices()
		{
			return $this->hasMany(InvoiceExpenses::class,'expense_id');
		}
		//
	}
