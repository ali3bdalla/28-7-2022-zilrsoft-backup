<?php
	
	namespace App;
	
	use App\Attributes\FilterValuesAttributes;
	use App\Relationships\FilterValuesRelationships;

	
	class FilterValues extends BaseModel
	{
		
		
		use FilterValuesRelationships,FilterValuesAttributes;
		protected $guarded = [
		
		];
		
		protected $appends = [
			'locale_name'
		];
		//
		

		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
			# code...
		}
	}
