<?php
	
	namespace App;
	
	use App\Attributes\KitItemsAttributes;
	use App\Relationships\KitItemsRelationships;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class KitItems extends Model
	{
		
		use KitItemsAttributes,KitItemsRelationships;
		
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
		//
	}
