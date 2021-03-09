<?php

	namespace App\Console;

    use App\Console\Commands\Order\CancelUnPaidOrderCommand;
    use App\Console\Commands\Order\NotifyUnPaidOrderCommand;
    use App\Console\Commands\Accounting\DailyUpdateAccountSnapshotCommand;
	use Illuminate\Console\Scheduling\Schedule;
	use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
			 $schedule->command(NotifyUnPaidOrderCommand::class)->everyMinute();
			 $schedule->command(CancelUnPaidOrderCommand::class)->everyMinute();

			 $schedule->command(DailyUpdateAccountSnapshotCommand::class)->daily();
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
