<?php

namespace App\Console\Commands;

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
	 
	          DB::unprepared(file_get_contents(base_path('database/types.sql')));
	          DB::unprepared(file_get_contents(base_path('database/gateways.sql')));
	          DB::unprepared(file_get_contents(base_path('database/countries.sql')));
//	     DB::unprepared(file_get_contents(base_path('database/roles.sql')));
	          DB::unprepared(file_get_contents(base_path('database/saudi_arabia.sql')));
	          DB::unprepared(file_get_contents(base_path('database/old_data.sql')));
	          DB::commit();
          }catch (\Exception $exception)
          {
          	
          	DB::rollBack();
          	
          }
	     
    }
}
