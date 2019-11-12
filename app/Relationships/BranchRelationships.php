<?php

namespace App\Relationships;

use App\Department;

trait BranchRelationships {

	public function departments()
	{
		return $this->hasMany(Department::class,'branch_id');
	}

}