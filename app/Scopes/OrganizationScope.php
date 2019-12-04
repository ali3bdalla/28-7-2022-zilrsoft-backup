<?php
	
	namespace App\Scopes;
	
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Scope;
	
	/**
	 *
	 */
	class OrganizationScope implements Scope
	{
		public $organization_id;
		
		public function __construct($organization_id)
		{
			$this->organization_id = $organization_id;
		}
		
		/**
		 * Apply the scope to a given Eloquent query builder.
		 *
		 * @param Builder $builder
		 * @param Model $model
		 *
		 * @return void
		 */
		public function apply(Builder $builder,Model $model)
		{
			
			$model->where('organization_id',$this->organization_id);
		}
	}
