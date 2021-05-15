<?php

namespace App\Console;

use App\Console\Commands\StocksReport;
use App\Models\Exchange;
use DateTimeZone;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

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
        $this->scheduleStocksUpdate($schedule);

        $this->scheduleStocksReport($schedule);
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

    /**
     * @param Schedule $schedule
     */
    private function scheduleStocksUpdate(Schedule $schedule): void
    {
        $betweenTimes = Cache::remember('scheduleFromTo', 86400, function () {
            $times = Exchange::select(['timezone', 'trading_from', 'trading_to'])->distinct()->get()->map(function (Exchange $exchange) {
                return [
                    'from' => new Carbon($exchange->trading_from, new DateTimeZone($exchange->timezone)),
                    'to' => new Carbon($exchange->trading_to, new DateTimeZone($exchange->timezone)),
                ];
            });

            return [
                'from' => $times->min('from'),
                'to' => $times->max('to'),
            ];
        });

        $schedule->command('stocks:update --all')->weekdays()->hourly()->between($betweenTimes['from'], $betweenTimes['to']);
    }

    private function scheduleStocksReport(Schedule $schedule)
    {
        $times = Cache::remember('scheduleReports', 10, function () {
            return Exchange::all()->mapToGroups(function (Exchange $exchange) {
                $endOfDay = new Carbon($exchange->trading_to, new DateTimeZone($exchange->timezone));
                return [
                    $endOfDay->getTimestamp() => $exchange->id,
                ];
            });
        });

        $times->each(function ($exchangeIds, $timestamp) use ($schedule) {
            /** @var Collection $exchangeIds */
            $timestamp = Carbon::createFromTimestamp($timestamp)->addMinutes(15);
            $options = $exchangeIds->values()->all();
            $options[] = '--email';

            $schedule
                ->command(StocksReport::class, $options)
                ->weekdays()
                ->timezone($timestamp->timezone)
                ->at($timestamp->toTimeString('minute'));
        });
    }
}
