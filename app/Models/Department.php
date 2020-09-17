<?php
	
	namespace App\Models;
	
	use App\Attributes\DepartmentAttributes;
	use App\Relationships\DepartmentRelationships;

	class Department extends BaseModel
	{
		//
		
		use DepartmentAttributes,DepartmentRelationships;
		
		protected $guarded = [];
		
		protected $appends = [
			'locale_title'
		];

	}
