<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

trait Nesting {
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
        $children_list = $model->children;
       if($children_list != null)
       {
//           dd(1);
           foreach ($children_list  as $child)
           {
               $child_children = self::getAllParentNestedChildren($child);
               if ($child_children != null)
                   $child['children'] = $child_children;

               $children[] = $child;
           }
       }

        if ($children == [])
            return null;

        return $children;
    }

    /**
     * @param Model $model
     * @return array|null
     */
    public static function infinityChildrenIds(Model $model)
    {
        $children = [];
        foreach ($model->children as $child){
            $child_children = self::infinityChildrenIds($child);
            if ($child_children != null)
                foreach ($child_children as $grand_child)
                    $children[] = $grand_child;

            $children[] = $child['id'];
        }
        if ($children == [])
            return null;


        return $children;
    }
}