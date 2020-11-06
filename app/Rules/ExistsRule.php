<?php
	
	namespace App\Rules;
	
	use Illuminate\Contracts\Validation\Rule;
	
	class ExistsRule implements Rule
	{
		/**
		 * @var string
		 */
		private $column;
		private $model;
		
		/**
		 * Create a new rule instance.
		 *
		 * @param $model
		 * @param string $column
		 */
		public function __construct($model, $column = 'id')
		{
			//
			$this->column = $column;
			$this->model = $model;
		}
		
		/**
		 * Determine if the validation rule passes.
		 *
		 * @param string $attribute
		 * @param mixed $value
		 * @return bool
		 */
		public function passes($attribute, $value)
		{
			$model = app($this->model);
			return $model->where($this->column, $value)->first() !== null;
		}
		
		/**
		 * Get the validation error message.
		 *
		 * @return string
		 */
		public function message()
		{
			return 'is not exists';
		}
		
	}
