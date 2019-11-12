<?php
namespace App\Relationships;
use App\Branch;
use App\Category;
use App\Department;
use App\Expense;
use App\Filter;
use App\Invoice;
use App\Item;
use App\Manager;
use App\OrganizationGateway;
use App\Payment;
use App\GatewayAccounts;
use App\Gateway;
use App\Role;
use App\SaleInvoice;
use App\User;
use App\Country;

trait OrganizationRelationships {

	public function country()
	{
		return $this->belongsTo(Country::class,'country_id');
	}
	
	public function categories(){
		return $this->hasMany(Category::class,'organization_id');
	}
	
	public function items(){
		return $this->hasMany(Item::class,'organization_id');
	}


	public function invoices(){
		return $this->hasMany(Invoice::class,'organization_id');
	}
	
	
	public function expenses(){
		return $this->hasMany(Expense::class,'organization_id');
	}
	
	public function branches()
	{
		return $this->hasMany(Branch::class,'organization_id');
	}
	
	
	public function departments()
	{
		return $this->hasMany(Department::class,'organization_id');
	}


	
	public function gateways()
	{
		return $this->belongsToMany(Gateway::class,
			'organization_gateway')->withTimestamps();
	}
	
	
	

	
    public function kits(){
        return $this->hasMany(Item::class,'organization_id');
    }


    public function accounts()
    {
        return $this->morphMany(GatewayAccounts::class, 'accountable');
    }


    public function sales(){
		return $this->hasMany(SaleInvoice::class,'organization_id');
	}


    public function payments(){
        return $this->hasMany(Payment::class,'organization_id');
    }



	public function users(){
		return $this->hasMany(User::class,'organization_id');
	}
	
	public function filters(){
		return $this->hasMany(Filter::class,'organization_id');
	}
	
	
	public function supervisor(){
		return $this->belongsTo(User::class,'supervisor_id');
	}



}
