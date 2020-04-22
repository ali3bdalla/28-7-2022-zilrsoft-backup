<?php
	
	namespace App;
	
	use App\Attributes\AccountAttributes;
	use App\Components\Model\Helper\NestedModelHelper;
	use App\Relationships\AccountRelationships;
	use Illuminate\Database\Eloquent\Model;
	
	class Account extends Model
	{
		
		use AccountAttributes,AccountRelationships,NestedModelHelper;
		
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
		
		public static function infinityChildrenIds(Account $category)
		{
			$children = [];
			foreach ($category->children as $child){
				$child_children = self::infinityChildrenIds($child);
				if ($child_children != null)
				{
					foreach ($child_children as $secound)
					{
						$children[] = $secound;
					}
				}
				$children[] = $child['id'];
			}
			
			if ($children == [])
				return null;
			
			return $children;
		}
		
		
		
		//
	}
