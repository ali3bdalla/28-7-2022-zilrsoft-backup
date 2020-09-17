<?php
	
	namespace App\Models;
	
	use App\Attributes\AccountAttributes;
    use App\Relationships\AccountRelationships;
	use Illuminate\Database\Eloquent\Model;

    /**
     * @method static where(array $array)
     */
    class Account extends BaseModel
	{
		
		use AccountAttributes,AccountRelationships;

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

