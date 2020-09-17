<?php
	
	namespace  App\Models;
	use Illuminate\Database\Eloquent\Model;

	use App\Attributes\CategoryAttributes;
    use App\Model\Nesting;
    use App\Relationships\CategoryRelationships;
	use Illuminate\Database\Eloquent\SoftDeletes;
	use \Modules\Web\Models\WebCategory;
	class Category extends BaseModel
	{
		//
		use CategoryRelationships,SoftDeletes,CategoryAttributes,Nesting;
		use WebCategory;
		protected $appends = [
			'locale_name',
			'label',
		];
		protected $guarded = [];
		


		public function returnNestedTreeIds(Model $model)
    {
        $result[] = $model->id;
        $children = $model->children;
        if($children != null) {
            foreach ($children as $builder_child)
                foreach ($this->returnNestedTreeIds($builder_child) as $id)
                    $result[] = $id;
        }
        return $result;
    }

    /**
     * @param Model $model
     * @return array|null
     */
    public static function getAllParentNestedChildren(Model $model)
    {
        $children = [];
        foreach ($model->children()->get() as $child){
            $child_children = self::getAllParentNestedChildren($child);
            if ($child_children != null)
                $child['children'] = $child_children;

            $children[] = $child;
        }


        return $children == [] ? null  : $children;
    }

    /**
     * @param Model $model
     * @return array|null
     */
    public static function infinityChildrenIds(Model $model)
    {
        $children = [];
        foreach ($model->children()->get() as $child){
            $child_children = self::infinityChildrenIds($child);
            if ($child_children != null)
                foreach ($child_children as $grand_child)
                    $children[] = $grand_child;

            $children[] = $child['id'];
        }

        return $children == [] ? null  : $children;
    }
	}
