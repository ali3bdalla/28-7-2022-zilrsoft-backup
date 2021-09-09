<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueRule implements Rule
{

    /**
     * @var string
     */
    private string $column;
    private string $model;

    /**
     * Create a new rule instance.
     *
     * @param string $model
     * @param string $column
     */
    public function __construct(string $model, string $column = 'id')
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
    public function passes($attribute, $value): bool
    {
        $model = app($this->model);
        return $model->where($this->column, $value)->first() === null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Already Exists';
    }
}
