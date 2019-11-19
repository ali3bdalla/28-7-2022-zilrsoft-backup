<?php
	
	namespace App;
	
	use App\Attributes\AccountAttributes;
	use App\Relationships\AccountRelationships;
	use Illuminate\Database\Eloquent\Model;
	
	class Account extends Model
	{
		
		use AccountAttributes,AccountRelationships;
		
		protected $guarded = [];
		
		protected $appends = [
			'locale_name',
			'total'
		];
		
		protected $casts = [
			'is_gateway' => 'boolean'
		];
		
		
		//
	}
