<?php

namespace App\Relationships;
use App\CountryBank;
use App\Invoice;
use App\InvoiceItems;
use \App\Organization;
use \App\Manager;
use App\GatewayAccounts;
use App\UserDetails;

trait UserRelationships
{
	
	public function details()
	{
		return $this->hasOne(UserDetails::class,'user_id');
	}

	public function organization(){
		return $this->belongsTo(Organization::class,'organizaiton_id');
	}
	
	public function client_history()
	{
		return $this->hasMany(InvoiceItems::class,'user_id')->whereIn('invoice_type',['sale','r_sale']);
	}

    public function creator()
    {
        return $this->belongsTo(Manager::class,'creator_id');
    }



    public function manager()
    {
        return $this->hasOne(Manager::class,'user_id');
    }


//
//    public function billings()
//    {
//        return $this->morphMany('App\Payment', 'billingable');
//    }


    public function accounts()
    {
        return $this->morphMany(GatewayAccounts::class, 'accountable');
    }
//
//	public function banks()
//	{
//		return $this->hasManyThrough(CountryBank::class,GatewayAccounts::class,'bank_id','')
//    }

}
