<?php

namespace App;

use App\Attributes\ChartAttributes;
use App\DatabaseHelpers\ChartHelper;
use App\Relationships\ChartRelationships;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
	
	use ChartAttributes,ChartHelper,ChartRelationships;
	
	
	protected $appends = [
		'locale_name',
		'title',
		'total'
	];
	
	protected $guarded = [];
}
