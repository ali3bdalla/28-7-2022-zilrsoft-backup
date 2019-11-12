<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	
	protected $guarded = [];
	
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


