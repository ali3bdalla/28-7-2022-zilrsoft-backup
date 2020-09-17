<?php

namespace App\Relationships;

use App\Models\Department;

trait BranchRelationships {

	public function departments()
	{
		return $this->hasMany(Department::class,'branch_id');
	}

}