<?php

namespace App\Models;


class Country extends BaseModel
{
	
	protected $guarded = [];
	
	protected $appends = ['locale_name'];
	public function banks()
	{
		return $this->hasMany(CountryBank::class,'country_id');
	}
	
	
	public function organizations()
	{
		return $this->hasMany(Organization::class,'country_id');
	}
    //
}


