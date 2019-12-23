<?php
	
	namespace App;
	
	use App\Attributes\CategoryAttributes;
	use App\Relationships\CategoryRelationships;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	class Category extends Model
	{
		//
		use CategoryRelationships,SoftDeletes,CategoryAttributes;
		
		protected $appends = [
			'locale_name',
			'label',
		];
		protected $fillable = [
			'name','ar_name','description','ar_description',
			'parent_id',
			'organization_id',
			'creator_id',
		
		];
		
		public static function getAllParentNestedChildren(Category $category)
		{
			$children = [];
			foreach ($category->children()->get() as $child){
				$child_children = self::getAllParentNestedChildren($child);
				if ($child_children != null)
					$child['children'] = $child_children;
				
				$children[] = $child;
			}
			
			if ($children == [])
				return null;
			
			return $children;
		}
		
		
		public static function infinityChildrenIds(Category $category)
		{
			$children = [];
			foreach ($category->children()->get() as $child){
				$child_children = self::infinityChildrenIds($child);
				if ($child_children != null)
				{
					foreach ($child_children as $secound)
					{
						$children[] = $secound;
					}
				}
				
//
				$children[] = $child['id'];
			}
			
			if ($children == [])
				return null;
			
			return $children;
		}
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
	}
