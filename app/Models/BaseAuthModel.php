<?php
	
	namespace App\Models;
	
	use App\Models\Traits\Translatable;
	use Carbon\Carbon;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	use Illuminate\Notifications\Notifiable;
	use Illuminate\Support\Facades\Schema;
	use Spatie\Permission\Traits\HasRoles;
	
	class BaseAuthModel extends Authenticatable
	{
		use Notifiable, HasRoles;
		use Translatable;
		
		protected $dateFormat = 'Y-m-d H:i:s';//O
		
		public function getUpdatedAtAttribute($value)
		{
			
			return Carbon::parse($value)->toDateTimeString();
		}
		public function getCreatedAtAttribute($value)
		{
			return Carbon::parse($value)->toDateTimeString();
		}
		protected static function boot()
		{
			parent::boot();
			$table = (new static)->getTable();
			if(auth()->guard('manager')->check() || auth()->user()) {
				if(Schema::hasColumn($table, 'organization_id')) {
					static::addGlobalScope(
						'organization', function(Builder $builder) use ($table) {
						$builder->where("{$table}.organization_id", auth()->user()->organization_id);
					}
					);
				}
				
			}
		}
	}
