<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
	protected $guarded = [];
	
	public function user()
	{
		return $this->hasOne(User::class,'user_id');
	}
    //
}
