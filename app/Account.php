<?php
	
	namespace App;
	
	use App\Attributes\AccountAttributes;
    use App\Model\Nesting;
    use App\Relationships\AccountRelationships;

    /**
     * @method static where(array $array)
     */
    class Account extends BaseModel
	{
		
		use AccountAttributes,AccountRelationships,Nesting;

		protected $guarded = [];
		
		protected $appends = [
			'locale_name',
			'current_amount',
			'label',
			'is_expanded',
		];
		protected $casts = [
			'is_gateway' => 'boolean'
		];
	}
