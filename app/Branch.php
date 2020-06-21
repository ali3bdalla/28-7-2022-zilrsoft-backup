<?php
	
	namespace App;
	
	use App\Attributes\BranchAttributes;
	use App\Relationships\BranchRelationships;
	use App\Scopes\OrganizationScope;

	class Branch extends BaseModel
	{
		//
		
		use BranchRelationships,BranchAttributes;
		
		protected $guarded = [];
		
		protected $appends= [
			'locale_name'
		];
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
	}
