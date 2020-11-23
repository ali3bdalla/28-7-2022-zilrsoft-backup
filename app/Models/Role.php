<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Model;
	use Spatie\Permission\Models\Role as BaseRole;
	
	class Role extends BaseRole
	{
		protected $dateFormat = 'Y-m-d H:i:sO';
		
	}
	
	
