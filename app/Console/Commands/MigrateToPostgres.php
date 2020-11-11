<?php
	
	namespace App\Console\Commands;
	
	use App\Models\BaseModel;
	use App\Models\User;
	use Illuminate\Console\Command;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Str;
	
	class MigrateToPostgres extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'command:migrate_to_postgresql';
		
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Command description';
		
		/**
		 * Create a new command instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
			parent::__construct();
		}
		
		/**
		 * Execute the console command.
		 *
		 * @return mixed
		 */
		public function handle()
		{


//			$path = app_path() . "/Models";
			
			$models = [
//				'App\Models\Account',
//				'App\Models\AccountSnapshot',
//				'App\Models\Attachment',
//				'App\Models\Bank',
//				'App\Models\Branch',
//				'App\Models\Category',
//				'App\Models\CategoryFilterValues',
//				'App\Models\CategoryFilters',
//				'App\Models\CategoryItems',
//				'App\Models\Country',
//				'App\Models\Department',
//				'App\Models\Expense',
//				'App\Models\Filter',
//				'App\Models\FilterValues',
//				'App\Models\HashMap',
				'App\Models\Invoice',
//				'App\Models\InvoiceExpenses',
//				'App\Models\InvoiceItems',
//				'App\Models\Item',
//				'App\Models\ItemExpenses',
//				'App\Models\ItemFilters',
//				'App\Models\ItemSerials',
//				'App\Models\ItemStatistic',
//				'App\Models\KitData',
//				'App\Models\KitItems',
//				'App\Models\Manager',
//				'App\Models\ManagerGateways',
//				'App\Models\Order',
//				'App\Models\Organization',
//				'App\Models\Payment',
//				'App\Models\Purchase',
//				'App\Models\ResellerClosingAccount',
//				'App\Models\Sale',
//				'App\Models\SerialHistory',
//				'App\Models\ShippingAddress',
//				'App\Models\ShippingMethod',
//				'App\Models\Transaction',
//				'App\Models\TransactionsContainer',
//				'App\Models\Type',
//				'App\Models\User',
//				'App\Models\UserDetails',
//				'App\Models\UserGateways',
//				'App\Models\WarrantySubscription'
			];


			
			foreach($models as $model) {
				$class = app($model);
				
				$tableName = $class->getTable();
				
//				$class::truncate();

				DB::connection('data_source')->table($tableName)->orderBy('created_at')->chunk(
					1000,
					function($data) use ($tableName, $class) {
						$class::withoutEvents(
							function() use ($data, $class, $tableName) {
								foreach($data as $item) {
//									if(!$class::find($item->id))
//									{
										$item = collect($item)->toArray();
										unset($item['id']);
										$query = new $class($item);
										$query->save();
										echo '  -  ' . $query->id .  '  -  ' .  $class::count() . "\n";
//									}
								}
							}
						);

					}
				);
//
				
			}


//			$rolesTables = ['roles',  'permissions', 'role_has_permissions','model_has_permissions', 'model_has_roles'];
////
//			foreach($rolesTables as $table) {
//				DB::table($table)->truncate();
//				$data = DB::connection('data_source')->table($table)->get();
//
//				foreach($data as $item) {
//					DB::table($table)->insert(collect($item)->toArray());
//				}
//
////				echo "'". $table . "',\n";
//			}
//
			
		}

//
//		public function getModels($path)
//		{
//			$out = [];
//			$results = scandir($path);
//			foreach($results as $result) {
//				if($result === '.' or $result === '..') continue;
//				$filename = $path . '/' . $result;
//				if(is_dir($filename)) {
////					$out = array_merge($out, $this->getModels($filename));
//				} else {
//					$out[] = "App" . str_replace('/', "\\", explode('/zilrsoft/app', substr($filename, 0, - 4))[1]);
//				}
//			}
//			return $out;
//		}




//
		
	}
