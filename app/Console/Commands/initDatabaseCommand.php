<?php
	
	namespace App\Console\Commands;
	
	use App\Navbar;
	use Exception;
	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\DB;
	
	class initDatabaseCommand extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'command:initdatabase';
		
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'to create countries and init types and also to create saudia arebia banks ';
		
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
			DB::beginTransaction();
			try{
			
//				Navba
//				DB::unprepared(file_get_contents(base_path('database/types.sql')));
//				DB::unprepared(file_get_contents(base_path('database/countries.sql')));
//				DB::unprepared(file_get_contents(base_path('database/saudi_arabia.sql')));
//				DB::unprepared(file_get_contents(base_path('database/old_data/categories.sql')));
//				DB::unprepared(file_get_contents(base_path('database/old_data/filters.sql')));
//				DB::unprepared(file_get_contents(base_path('database/old_data/filters_values.sql')));
//				DB::unprepared(file_get_contents(base_path('database/old_data/categories_filters.sql')));
//				DB::unprepared(file_get_contents(base_path('database/old_data/item.sql')));
//				DB::unprepared(file_get_contents(base_path('database/old_data/item_filters.sql')));
//				DB::unprepared(file_get_contents(base_path('database/clear.sql')));
//				DB::unprepared("
//INSERT INTO `organizations` (`id`, `title`, `title_ar`, `city`, `city_ar`, `description`, `description_ar`, `type`, `country_id`, `type_id`, `supervisor_id`, `logo`, `address`, `address_ar`, `phone_number`, `stamp`, `vat`, `cr`, `created_at`, `updated_at`) VALUES
//(1, 'Bait Almesbar For Trading', 'مؤسسة بيت المسبار التجارية', 'ArRass', 'الرس', 'الكترونيات - انظمة امنية - كومبيوتر - جوالات', NULL, 'establishment', 1, 1, 1, NULL, 'King Fahad Street - west civil affairs', 'طريق الملك فهد -  غرب الاحوال المدنية', '0163394000', NULL, '301032266600003', '1132002748', '2019-11-04 19:42:19', '2019-11-04 19:42:19');
//");
				DB::commit();
			}catch (Exception $exception){
				
				dd($exception->getMessage());
				DB::rollBack();
				
			}
			
		}
	}
