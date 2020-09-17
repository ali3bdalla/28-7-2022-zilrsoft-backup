<?php

namespace App\Models;


class UserDetails extends BaseModel
{
	protected $guarded = [];
	
	public function user()
	{
		return $this->hasOne(User::class,'user_id');
	}
}
