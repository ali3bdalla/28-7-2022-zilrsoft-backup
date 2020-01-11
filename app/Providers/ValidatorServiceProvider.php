<?php
	
	namespace App\Providers;
	
	use App\Account;
	use App\Item;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Support\ServiceProvider;
	
	class ValidatorServiceProvider extends ServiceProvider
	{
		/**
		 * Register services.
		 *
		 * @return void
		 */
		public function register()
		{
			
			
			//
		}
		
		/**
		 * Bootstrap services.
		 *
		 * @return void
		 */
		public function boot()
		{
			
			Validator::extendImplicit('phone',function ($attribute,$value,$parameters){
				return true;
			});
			Validator::extendImplicit('price',function ($attribute,$value,$args){
				
				return preg_match('/^\d{0,8}(\.\d{1,8})?$/',$value);
			});
			
			Validator::extendImplicit('item_has_available_qty',function ($attribute,$value,$args){
				$str_attr = explode('.',$attribute);
				$index = $str_attr[1];
				$first = $str_attr[0];
				$end = $str_attr[2];
				$id = request()->input("{$first}.{$index}.id");
				$item = Item::findOrFail($id);
				return $item->is_kit || $item->is_service || $item->is_expense ? true : $item->available_qty >=
					$value;
			});
			
			
			Validator::extendImplicit('validate_item_price_or_discount',function ($attribute,$value,$args){
				$str_attr = explode('.',$attribute);
				$index = $str_attr[1];
				$first = $str_attr[0];
				$end = $str_attr[2];
				$id = request()->input("{$first}.{$index}.id");
				$item = Item::findOrFail($id);
				
				if ($end == "price")
					return $item->is_kit ? true : is_numeric($value);
				else{
					return !$item->is_kit && !$item->is_expense ? is_numeric($value) : true;
				}
				
			});
			
			
			Validator::extendImplicit('validate_item_purchase_price',function ($attribute,$value,$args){
				$str_attr = explode('.',$attribute);
				$index = $str_attr[1];
				$first = $str_attr[0];
				$end = $str_attr[2];
				$id = request()->input("{$first}.{$index}.id");
				$item = Item::findOrFail($id);
				return $item->is_expense ? is_numeric($value) && !empty($value) : true;
			});
			
			Validator::extendImplicit('validate_expense_vendor',function ($attribute,$value,$args){
				$str_attr = explode('.',$attribute);
				$index = $str_attr[1];
				$first = $str_attr[0];
				$end = $str_attr[2];
				$id = request()->input("{$first}.{$index}.id");
				$item = Item::findOrFail($id);
//				return true;
				return $item->is_expense ? is_numeric($value) && !empty($value) : true;
			});
			
			
			Validator::extendImplicit('validate_serials_array',function ($attribute,$value,$args,$validator){
				$str_attr = explode('.',$attribute);
				$index = $str_attr[1];
				$first = $str_attr[0];
				$id = request()->input("{$first}.{$index}.id");
				$qty = request()->input("{$first}.{$index}.qty");
				$item = Item::findOrFail($id);;
				return $item->is_need_serial ? !empty($value) && count($value) == $qty : true;
				
			});
			
			
			Validator::extend('validate_item_serials',function ($attribute,$value,$args){
				$str_attr = explode('.',$attribute);
				$index = $str_attr[1];
				$first = $str_attr[0];
				$id = request()->input("{$first}.{$index}.id");
				$item = Item::findOrFail($id);
				if ($item->is_need_serial){
					$db_serial = $item->serials()->where('serial',$value)->first();
					if (empty($db_serial))
						return false;
					else{
						if (in_array($db_serial->current_status,["r_purchase","saled"])){
							return false;
						}
					}
					
				}
				return true;
			});
			
			
			
			//
		}
	}
