<?php

namespace App\Models;


class Bank extends BaseModel
{
	protected $guarded = [];
	
	protected $appends = [
		'locale_name'
	];
	public function getLocaleNameAttribute()
	{
		return $this->ar_name;
	}

	public function account()
	{
		return $this->belongsTo(Account::class);
	}
    //
}
