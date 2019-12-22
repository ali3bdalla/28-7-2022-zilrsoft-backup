<?php
	
	namespace App;
	
	use App\Attributes\BranchAttributes;
	use App\Relationships\BranchRelationships;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class Branch extends Model
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
