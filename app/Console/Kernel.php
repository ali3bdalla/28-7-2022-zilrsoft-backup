<?php
	
	namespace App\Console;
	
	use App\Console\Commands\Order\CancelUnPaidOrder;
	use App\Console\Commands\Order\NotifyUnPaidOrder;
	use Illuminate\Console\Scheduling\Schedule;
	use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
	use Illuminate\Support\Facades\Storage;
	
	class Kernel extends ConsoleKernel
	{
		/**
		 * The Artisan commands provided by your application.
		 *
		 * @var array
		 */
		protected $commands = [
			//
		];
		
		/**
		 * Define the application's command schedule.
		 *
		 * @param Schedule $schedule
		 * @return void
		 */
		protected function schedule(Schedule $schedule)
		{
			// $schedule->command(NotifyUnPaidOrder::class)->everyMinute();
			$schedule->command(CancelUnPaidOrder::class)->everyMinute();

		}
		
		/**
		 * Register the commands for the application.
		 *
		 * @return void
		 */
		protected function commands()
		{
			$this->load(__DIR__ . '/Commands');
			
			require base_path('routes/console.php');
		}
	}
