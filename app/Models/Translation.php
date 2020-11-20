<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    //
	
	protected $guarded = [];
	
	
	public function translatable()
	{
		return $this->morphTo('translatable');
	}
}
