<?php
	
	namespace App;
	
	use App\Attributes\CategoryAttributes;
    use App\Model\Nesting;
    use App\Relationships\CategoryRelationships;
	use Illuminate\Database\Eloquent\SoftDeletes;
	use \Modules\Web\Models\WebCategory;
	class Category extends BaseModel
	{
		//
		use CategoryRelationships,SoftDeletes,CategoryAttributes,Nesting;
		use WebCategory;
		protected $appends = [
			'locale_name',
			'label',
		];
		protected $guarded = [];
		

	}
