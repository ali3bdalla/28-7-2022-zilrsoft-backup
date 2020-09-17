<?php

namespace App\Attributes;

trait CategoryAttributes {

	public function scopeMainOnly($query)
    {
        return $query->where('parent_id', 0);
    }

   
	
	public function  getLocaleNameAttribute(){
		
		if(app()->isLocale('ar')){
			return $this->ar_name;
		}
		
		return $this->name;
	}
	
	public function  getLabelAttribute(){
		
		
		
		return $this->locale_name;
	}
	
	
}
