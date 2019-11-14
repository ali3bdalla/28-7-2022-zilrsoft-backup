<?php
	
	namespace App;
	
	use App\Attributes\UserAttributes;
	use App\DatabaseHelpers\UserHelper;
	use App\Relationships\UserRelationships;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	
	class User extends Model
	{
		
		use SoftDeletes,UserRelationships,UserAttributes,UserHelper;
		
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}

		public function getCreatedDateAttribute()
		{
			return $this->created_at->diffForHumans();
		}
	}
