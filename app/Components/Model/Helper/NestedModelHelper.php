<?php
	
	
	namespace App\Components\Model\Helper;
	
	
	use Illuminate\Database\Eloquent\Model;
	
	trait NestedModelHelper
	{
		
		public function nestedChildrenIdentifers(Model $builder)
		{
		
			$ids_list[] = $builder->id;
			
			foreach ($builder->children()->get() as $builder_child)
			{
				foreach ($this->nestedChildrenIdentifers($builder_child) as $id)
					$ids_list[] = $id;
			}
			
			return $ids_list;
		}
	}