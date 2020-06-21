<?php
	
	namespace App;
	
	use App\Attributes\DepartmentAttributes;
	use App\Relationships\DepartmentRelationships;
	use App\Scopes\OrganizationScope;

	class Department extends BaseModel
	{
		//
		
		use DepartmentAttributes,DepartmentRelationships;
		
		protected $guarded = [];
		
		protected $appends = [
			'locale_title'
		];

	}
