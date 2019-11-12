<?php

namespace App\Relationships;
use App\Gateway;
use \App\Organization;
use \App\User;
use \App\Role;
use \App\Filter;
use \App\FilterValues;
use \App\Department;
use \App\Branch;
use \App\Category;

trait ManagerRelationships
{
	
	
	
	public function gateways()
	{
		return $this->belongsToMany(User::class,
			'organization_gateway'
		);
	}
	
	
	
	public function user(){
		return $this->belongsTo(User::class,'user_id');
	}

    public function department(){
        // return 1;
        return $this->belongsTo(Department::class,'department_id');
    }

    public function branch(){
        // return 1;
        return $this->belongsTo(Branch::class,'branch_id');
    }





	
	public function organization(){
		return $this->belongsTo(Organization::class,'organization_id');
	}





    public function roles()
    {
        return $this->belongsToMany(Role::class,'manager_role','manager_id','role_id');
    }



    public function categories()
    {
        return $this->hasMany(Category::class,'creator_id');
    }



    public function filters()
    {
        return $this->hasMany(Filter::class,'creator_id');
    }

    public function filters_values()
    {
        return $this->hasMany(FilterValues::class,'creator_id');
    }
}
