<?php
	
	namespace App;
	
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Auth;
	
	class ManagerPrivateTransactions extends Model
	{
		
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (Auth::check()){
				static::addGlobalScope(new OrganizationScope(Auth::user()->organization_id));
				
			}
		}
		//
	}
