<?php
	
	namespace App;
	
	use App\Attributes\ManagerAttributes;
	use App\Relationships\ManagerRelationships;
	use App\Scopes\OrganizationScope;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	use Illuminate\Notifications\Notifiable;
	use Illuminate\Support\Facades\Auth;
	
	class Manager extends Authenticatable
	{
		use Notifiable,ManagerRelationships,ManagerAttributes;
		
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
		
		protected static function boot()
		{
			parent::boot();
			if (Auth::check()){
				static::addGlobalScope(new OrganizationScope(Auth::user()->organization_id));
				
			}
		}
		
	}
