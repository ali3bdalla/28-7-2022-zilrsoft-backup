<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Factories\Factory;
abstract class BaseFactory extends  Factory
{
    public abstract function setDto(BaseDtoContract $dto);
}
