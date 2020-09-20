<?php

namespace App\Models;

use App\Relationships\SaleRelationships;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends BaseModel
{
    //
    use SaleRelationships;
    use SoftDeletes;
    protected $guarded = [];


}
