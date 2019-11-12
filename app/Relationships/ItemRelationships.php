<?php


namespace App\Relationships;


use App\Invoice;
use App\InvoiceItems;
use App\ItemSerials;
use \App\Organization;
use \App\Category;
use \App\Manager;
use \App\ItemFilters;
use App\ItemExpenses;

trait ItemRelationships
{
	
	
	
	public function expenses()
	{
		return $this->hasMany(ItemExpenses::class,'item_id');
	}
	

	public function organization(){
		return $this->belongsTo(Organization::class,'organizaiton_id');
	}

	public function serials(){
	    return $this->hasMany(ItemSerials::class,'item_id');
    }



    public function history(){
        return $this->hasMany(InvoiceItems::class,'item_id');
    }




	public function category(){
		return $this->belongsTo(Category::class,'category_id');
	}


	public function filters(){
		return $this->hasMany(ItemFilters::class,'item_id');
	}


    public function creator(){
        return $this->belongsTo(Manager::class,'creator_id');
    }









}
