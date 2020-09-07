<?php
	
	namespace App;
	
	use App\Attributes\AccountAttributes;
    use App\Model\Nesting;
    use App\Relationships\AccountRelationships;
    use App\Traits\OrmNumbersTrait;

    /**
     * @method static where(array $array)
     */
    class Account extends BaseModel
	{
		
		use AccountAttributes,AccountRelationships,Nesting,OrmNumbersTrait;

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
