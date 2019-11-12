<?php
	
	namespace App;
	
	use App\Attributes\FilterValuesAttributes;
	use App\Relationships\FilterValuesRelationships;
	use App\Scopes\OrganizationScopeForRelationships;
	use Illuminate\Database\Eloquent\Model;
	
	
	class FilterValues extends Model
	{
		//
		use FilterValuesRelationships,FilterValuesAttributes;
		protected $guarded = [
		
		];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScopeForRelationships(auth()->user()->organization_id));
			}
		}
		
		// relatioships
		
		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
			# code...
		}
	}
