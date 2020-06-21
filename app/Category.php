<?php
	
	namespace App;
	
	use App\Attributes\CategoryAttributes;
    use App\Model\Nesting;
    use App\Relationships\CategoryRelationships;
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	class Category extends BaseModel
	{
		//
		use CategoryRelationships,SoftDeletes,CategoryAttributes,Nesting;
		
		protected $appends = [
			'locale_name',
			'label',
		];
		protected $fillable = [
			'name','ar_name','description','ar_description',
			'parent_id',
			'organization_id',
			'creator_id',
		
		];
		

		

		
	}
