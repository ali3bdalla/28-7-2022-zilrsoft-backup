<?php

namespace App;


class WarrantySubscription extends BaseModel
{
	
	protected $guarded = [];
	
	protected $appends = [
		'locale_name'
	];
	
	public function getLocaleNameAttribute()
	{
		if(app()->isLocale('ar'))
			return $this->ar_name;
		
		return $this->name;
	}
	
	public function items()
	{
		return $this->hasMany(Item::class,'warranty_subscription_id');
	}
	
	public static function createInit()
	{
		$data = array(
			array('ar_name' => 'ضمان سنتين وكيل','name' => '2 years agent warranty','organization_id' => auth()
				->user()->organization_id),
			array('ar_name' => 'ضمان سنتين محل','name' => '2 years store warranty','organization_id' => auth()->user()->organization_id),
			array('ar_name' => 'ضمان سنة وكيل','name' => '1 year agent warranty','organization_id' => auth()->user()->organization_id),
			array('ar_name' => 'ضمان سنة محل','name' => '1 year store warranty','organization_id' => auth()->user()->organization_id),
			array('ar_name' => 'ضمان ٦ شهور وكيل','name' => '6 months agent warranty','organization_id' => auth()->user()->organization_id),
		);
		Self::insert($data);
		
	}
    //
}
