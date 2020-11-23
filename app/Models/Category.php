<?php
	
	namespace App\Models;
	
	use App\Models\Traits\NestingTrait;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	
	/**
	 * @property mixed locale_name
	 */
	class Category extends BaseModel
	{
		//
		use  SoftDeletes, NestingTrait;
		
		
		protected $appends = [
			'locale_name',
			'label',
			'products_count'
		];
		protected $guarded = [];
		
		/**
		 * @param Model $model
		 * @return array|null
		 */
		public static function getAllParentNestedChildren(Model $model)
		{
			$children = [];
			foreach($model->children()->get() as $child) {
				$child_children = self::getAllParentNestedChildren($child);
				if($child_children != null) {
					$child['children'] = $child_children;
				}
				
				$children[] = $child;
			}
			
			return $children == [] ? null : $children;
		}
		
		/**
		 * @param Model $model
		 * @return array|null
		 */
		public static function infinityChildrenIds(Model $model)
		{
			$children = [];
			foreach($model->children()->get() as $child) {
				$child_children = self::infinityChildrenIds($child);
				if($child_children != null) {
					foreach($child_children as $grand_child) {
						$children[] = $grand_child;
					}
				}
				
				$children[] = $child['id'];
			}
			
			return $children == [] ? null : $children;
		}
		
		public function returnNestedTreeIds(Model $model)
		{
			$result[] = $model->id;
			$children = $model->children;
			if($children != null) {
				foreach($children as $builder_child) {
					foreach($this->returnNestedTreeIds($builder_child) as $id) {
						$result[] = $id;
					}
				}
				
			}
			return $result;
		}
		
		public function getLocaleNameAttribute()
		{
			
			if(app()->isLocale('ar')) {
				return $this->ar_name;
			}
			
			return $this->name;
		}
		
		public function getLabelAttribute()
		{
			
			
			return $this->locale_name;
		}
		
		public function children()
		{
			return $this->hasMany(Category::class, 'parent_id');
		}
		
		
		public function filters()
		{
			return $this->belongsToMany(Filter::class, 'category_filters', 'category_id', 'filter_id')->withTimestamps()->orderBy('category_filters.id', 'asc');
		}
		
		public function filtersValues()
		{
			return $this->hasMany(CategoryFilterValues::class,'category_id');
		}
		
		public function items()
		{
			return $this->hasMany(Item::class, 'category_id');
		}
		
		/*web*/
		
		public function getProductsCountAttribute()
		{
			return Item::whereIn('category_id', $this->getChildrenIncludeMe())->count();
		}
	}

