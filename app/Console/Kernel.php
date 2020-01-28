<?php

namespace App\Console;

use App\Exchange;
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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () use ($schedule) {
            Exchange::select(['timezone', 'trading_from', 'trading_to'])->distinct()->each(function (Exchange $exchange) use ($schedule) {
                $schedule->command('stocks:update --all')->timezone($exchange->timezone)->between($exchange->trading_from, $exchange->trading_to);
            });
        })->weekdays()->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
