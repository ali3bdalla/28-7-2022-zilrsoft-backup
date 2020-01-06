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
			'current_amount',
			'label'
		];
		
		protected $casts = [
			'is_gateway' => 'boolean'
		];
		
		public static function getAllParentNestedChildren(Account $account)
		{
			$children = [];
			foreach ($account->children()->get() as $child){
				$child_children = self::getAllParentNestedChildren($child);
				if ($child_children != null)
					$child['children'] = $child_children;
				
				$children[] = $child;
			}
			
			if ($children == [])
				return null;
			
			return $children;
		}
		
		
		//
	}
