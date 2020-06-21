<?php
	
	namespace App;
	
	use App\Attributes\FilterAttributes;
	use App\Relationships\FilterRelationships;
	use App\Scopes\OrganizationScopeForRelationships;

	class Filter extends BaseModel
	{
		//
		use FilterRelationships,FilterAttributes;
		
		protected $appends = [
			'locale_name',
		];
		protected $guarded = [
		];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScopeForRelationships(auth()->user()->organization_id));
			}
		}
		
	}
