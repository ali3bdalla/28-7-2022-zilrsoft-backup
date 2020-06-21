<?php
	
	namespace App;
	
	use App\Attributes\ManagerAttributes;
	use App\Relationships\ManagerRelationships;

	
	class Manager extends BaseAuthModel
	{
		use ManagerRelationships,ManagerAttributes;
		
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $guarded = [];
		/**
		 * The attributes that should be hidden for arrays.
		 *
		 * @var array
		 */
		protected $hidden = [
			'password','remember_token',
		];
		/**
		 * The attributes that should be cast to native types.
		 *
		 * @var array
		 */
		protected $casts = [
			'email_verified_at' => 'datetime',
		];
		protected $appends = [
			'locale_name'
		];
		

		
	}
