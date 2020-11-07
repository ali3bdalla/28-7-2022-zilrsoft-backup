<?php
	
	namespace App\Console\Commands;
	
	use App\Models\Category;
	use App\Models\CategoryFilters;
	use App\Models\Filter;
	use Illuminate\Console\Command;
	
	class AddModelNumberToCategoriesCommand extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'command:add_model_number_to_categories';
		
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
			foreach(Category::all() as $category) {
				$requiredFilter = Filter::where('is_required_filter', true)->pluck('id')->toArray();
				$categoryFilters = CategoryFilters::where('category_id', $category->id)->pluck('filter_id')->toArray();
				
				foreach($requiredFilter as $filterId) {
					if(!in_array($filterId, $categoryFilters)) {
						$category->filters()->attach(
							$filterId, [
								'organization_id' => 1,
								'creator_id' => 1,
								'sorting' => 0
							]
						);
					}
				}
				
				$category->update([
					'description' => $category->name,
					'ar_description' => $category->ar_name,
				]);
				
			}
		}
	}
