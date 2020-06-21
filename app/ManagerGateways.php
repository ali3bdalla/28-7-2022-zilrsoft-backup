<?php

namespace App;


class ManagerGateways extends BaseModel
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
