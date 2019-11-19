<?php

namespace App\Attributes;

trait CategoryAttributes {

	public function scopeMainOnly($query)
    {
        return $query->where('parent_id', 0);
    }

    public function cloneFiltersFromAnotherCategory($pcategory){

	    if(empty($pcategory))
	        return;// if category not exists return


        $filters = $pcategory->filters()->pluck('filter_id');
        $this->filters()->attach($filters,
            [
                'organization_id'=>auth()->user()->organization_id,
                'creator_id'=>auth()->user()->id,
                'sorting'=>0
            ]
        );
    }
	
	
	
	public function  getLocaleNameAttribute(){
		
		if(app()->isLocale('ar')){
			return $this->ar_name;
		}
		
		return $this->name;
	}
	
	
}
