<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagerGateways extends Model
{
    //
	protected $guarded = [];
	
	public function gateway()
	{
		return $this->belongsTo(Account::class,'gateway_id');
	}
	
	
	
	public function manager()
	{
		return $this->belongsTo(Manager::class,'manager_id');
	}
	
}
