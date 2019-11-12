<?php
	
	namespace App;
	
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class Role extends Model
	{
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		//
	}
