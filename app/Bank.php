<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
	protected $guarded = [];
	
	protected $appends = [
		'locale_name'
	];
	public function getLocaleNameAttribute()
	{
		return $this->ar_name;
	}
    //
}
