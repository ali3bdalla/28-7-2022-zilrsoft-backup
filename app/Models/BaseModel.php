<?php
	
	namespace App\Models;
	
	use App\Models\Traits\PostgresTimestamp;
	use App\Models\Traits\Translatable;
	use Carbon\Carbon;
	use Carbon\CarbonInterface;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Date;
	use Illuminate\Support\Facades\Schema;
	
	class BaseModel extends Model
	{
		use Translatable, PostgresTimestamp;
		
		private static $customTablesOrder = ['accounts' => 'serial'];
		
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
				
			} else {
				if(Schema::hasColumn($table, 'organization_id')) {
					static::addGlobalScope(
						'organization', function(Builder $builder) use ($table) {
						$builder->where("{$table}.organization_id", 1);
					}
					);
				}
			}
			
			
			if(Schema::hasColumn($table, 'is_draft')) {
				static::addGlobalScope(
					'draft', function(Builder $builder) use ($table) {
					$builder->where("{$table}.is_draft", false);
				}
				);
			}
			
			if(auth()->check() && Schema::hasColumn($table, 'is_pending')) {
				static::addGlobalScope(
					'pending', function(Builder $builder) use ($table) {
					$builder->where("{$table}.is_pending", false);
				}
				);
			}

			
			foreach(self::$customTablesOrder as $key => $order) {
				if($key == $table) {
					static::addGlobalScope(
						'order', function(Builder $builder) use ($order, $table) {
						$builder->orderBy("{$table}.{$order}", 'desc');
					}
					);
				}
			}
			
			if(auth('manager')->check() && !auth('manager')->user()->can('manage branches') && $table == 'invoices') {
				if(Schema::hasColumn($table, 'creator_id')) {
					static::addGlobalScope(
						'manager', function(Builder $builder) use ($table) {
						$builder->where("{$table}.creator_id", auth()->user()->id);
					}
					);
				}
				
				
				if($table === "orders") static::addGlobalScope(
					'manager', function(Builder $builder) use ($table) {
					$builder->where("{$table}.managed_by_id", auth()->user()->id);
				}
				);
				
			}
			
			
			if(strpos(url()->current(), 'images_upload')) {
				if($table == 'items') {
					static::addGlobalScope(
						'online', function(Builder $builder) use ($table) {
						$builder->where(
							[
								["{$table}.available_qty", '>', 0],
							]
						)->with('category')->whereHas('category');
					}
					);
					
				}
				
			}
			
			
			if(strpos(url()->current(), 'web')) {
				if($table == 'items') {
					static::addGlobalScope(
						'online', function(Builder $builder) use ($table) {
						$builder->where(
							[
								["{$table}.is_kit", false],
							]
						)->with('category')->whereHas('category');
					}
					);
					
				}
				
				
				if($table == 'categories') {
					static::addGlobalScope(
						'online', function(Builder $builder) use ($table) {
						$builder->where(
							[
								["{$table}.is_available_online", true],
							]
						);
					}
					);
					
				}
				
				
			}
			
			
		}
		
		
	}
