<?php
	
	namespace App\Models;
	
	use App\Models\Traits\Translatable;
	use Carbon\Carbon;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Schema;
	
	class BaseModel extends Model
	{
		use Translatable;
		
		private static $customTablesOrder = ['accounts' => 'serial'];
//		public $timestamps = true;
//		protected $casts = [
//			'created_at' => 'datetime',
//			'updated_at' => 'datetime',
//		];//O
		protected $dateFormat = 'Y-m-d H:i:sO';//O
		
//		protected static function boot()
//		{
//			parent::boot();
//			$table = (new static)->getTable();
//			static::creating(
//				function(Model $model) {
//					Model::withoutEvents(
//						function() use ($model) {
////							dd($model);
//
//							$model->created_at = Carbon::now();
//							$model->updated_at = Carbon::now();
//							$model->save();
////							$model->save();
//						}
//					);
//				}
//			);
//			if(auth()->guard('manager')->check() || auth()->user()) {
//				if(Schema::hasColumn($table, 'organization_id')) {
//					static::addGlobalScope(
//						'organization', function(Builder $builder) use ($table) {
//						$builder->where("{$table}.organization_id", auth()->user()->organization_id);
//					}
//					);
//				}
//
//			} else {
//				if(Schema::hasColumn($table, 'organization_id')) {
//					static::addGlobalScope(
//						'organization', function(Builder $builder) use ($table) {
//						$builder->where("{$table}.organization_id", 1);
//					}
//					);
//				}
//			}
//
//
//			if(Schema::hasColumn($table, 'is_draft')) {
//				static::addGlobalScope(
//					'draft', function(Builder $builder) use ($table) {
//					$builder->where("{$table}.is_draft", false);
//				}
//				);
//			}
//
//			if(auth()->check() && Schema::hasColumn($table, 'pending')) {
//				static::addGlobalScope(
//					'pending', function(Builder $builder) use ($table) {
//					$builder->where("{$table}.is_pending", false);
//				}
//				);
//			}
//
////			$disabledDefaultSoringTables = ['invoice_items'];
////			if(!key_exists($table, self::$customTablesOrder) && !in_array($table,$disabledDefaultSoringTables) {
////				static::addGlobalScope(
////					'order', function(Builder $builder) use ($table) {
//////						dd($builder);
////					$builder->orderBy("{$table}.created_at", 'desc');
////				}
////				);
////			}
//
//			foreach(self::$customTablesOrder as $key => $order) {
//				if($key == $table) {
//					static::addGlobalScope(
//						'order', function(Builder $builder) use ($order, $table) {
//						$builder->orderBy("{$table}.{$order}", 'desc');
//					}
//					);
//				}
//			}
//
//			if(auth()->check() && !auth()->user()->can('manage branches') && $table == 'invoices') {
//				if(Schema::hasColumn($table, 'creator_id')) {
//					static::addGlobalScope(
//						'manager', function(Builder $builder) use ($table) {
//						$builder->where("{$table}.creator_id", auth()->user()->id);
//					}
//					);
//				}
//
//
//				if($table === "orders") static::addGlobalScope(
//					'manager', function(Builder $builder) use ($table) {
//					$builder->where("{$table}.managed_by_id", auth()->user()->id);
//				}
//				);
//
//			}
//
//
//			if(strpos(url()->current(), 'images_upload')) {
//				if($table == 'items') {
//					static::addGlobalScope(
//						'online', function(Builder $builder) use ($table) {
//						$builder->where(
//							[
//								["{$table}.available_qty", '>', 0],
//							]
//						)->with('category')->whereHas('category');
//					}
//					);
//
//				}
//
//			}
//
//
//			if(strpos(url()->current(), 'web')) {
//				if($table == 'items') {
//					static::addGlobalScope(
//						'online', function(Builder $builder) use ($table) {
//						$builder->where(
//							[
//								["{$table}.is_kit", false],
//							]
//						)->with('category')->whereHas('category');
//					}
//					);
//
//				}
//
//
//				if($table == 'categories') {
//					static::addGlobalScope(
//						'online', function(Builder $builder) use ($table) {
//						$builder->where(
//							[
//								["{$table}.is_available_online", true],
//							]
//						);
//					}
//					);
//
//				}
//
//
//			}
//
//
//		}
//
//
		public function getUpdatedAtAttribute($value)
		{
			return Carbon::parse($value)->toDateTimeString();
		}
		
		public function getCreatedAtAttribute($value)
		{
			return Carbon::parse($value)->toDateTimeString();
		}
		
		
	}
