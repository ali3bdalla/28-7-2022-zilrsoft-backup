<?php

namespace App\Console;

use App\Console\Commands\Accounting\DailyUpdateAccountSnapshotCommand;
use App\Console\Commands\Item\UpdateGoogleShippingCommand;
use App\Console\Commands\Order\CancelUnPaidOrderCommand;
use App\Console\Commands\Order\NotifyUnPaidOrderCommand;
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
        $schedule->command(UpdateGoogleShippingCommand::class)->daily();
        $schedule->command(DailyUpdateAccountSnapshotCommand::class)->daily();
        if ($this->app->environment('production')) {
            $schedule->command('scout:import')->daily();
        }
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
