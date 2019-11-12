<?php
	
	namespace App\Scopes;
	
	use Illuminate\Database\Eloquent\Scope;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Builder;
	
	/**
	 *
	 */
	class CountryScope implements Scope
	{
		
		private $country_id;
		public function __construct($country_id)
		{
		
			$this->country_id = $country_id;
		}
		


		public function apply(Builder $builder, Model $model)
		{
			$builder->where('country_id',$this->country_id);
		}
	}
