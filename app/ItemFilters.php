<?php
	
	namespace App;
	
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class ItemFilters extends Model
	{
		
		protected $fillable = [
			'organization_id',
			'creator_id',
			'filter_id',
			'filter_value',
			'item_id',
		];
		
		//
		protected $casts = [
			'id' => 'integer',
			'filter_value' => 'integer',
			'filter_id' => 'integer',
			'item_id' => 'integer'
		
		];

		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
	}
