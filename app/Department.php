<?php
	
	namespace App;
	
	use App\Attributes\DepartmentAttributes;
	use App\Relationships\DepartmentRelationships;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class Department extends Model
	{
		//
		
		use DepartmentAttributes,DepartmentRelationships;
		
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
	}
