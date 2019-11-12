<?php
	
	namespace App;
	
	use App\Attributes\CategoryAttributes;
	use App\Relationships\CategoryRelationships;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	class Category extends Model
	{
		//
		use CategoryRelationships,SoftDeletes,CategoryAttributes;
		
		protected $appends = [
			'locale_name'
		];
		protected $fillable = [
			'name','ar_name','description','ar_description',
			'parent_id',
			'organization_id',
			'creator_id'
		];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
	}
