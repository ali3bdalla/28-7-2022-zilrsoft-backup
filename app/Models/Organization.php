<?php
	
	namespace App\Models;
	
	use App\Relationships\OrganizationRelationships;

	class Organization extends BaseModel
	{

		use OrganizationRelationships;

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $guarded = [];
		
		public function getOrganizationTaxAttribute()
		{
			return 1.05;
		}
		
		
		public function getOrganizationVatAttribute()
		{
			return 15;
		}
	}
