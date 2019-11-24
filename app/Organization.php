<?php
	
	namespace App;
	
	use App\DatabaseHelpers\OrganizationCreationHelper;
	use App\Relationships\OrganizationRelationships;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Foundation\Testing\WithFaker;
	
	class Organization extends Model
	{
		
		//
		use OrganizationRelationships,OrganizationCreationHelper;
		
		use WithFaker;
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
			return 5;
		}
	}
