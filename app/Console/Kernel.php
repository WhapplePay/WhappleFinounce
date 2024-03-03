<?php

namespace App\Console;

use App\Console\Commands\BinanceGetStatus;
use App\Console\Commands\BlockIoIPN;
use App\Console\Commands\Cron;
use App\Console\Commands\CryptoRateUpdate;
use App\Console\Commands\FiatRateUpdate;
use App\Models\Gateway;
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
        BlockIoIPN::class,
        Cron::class,
        BinanceGetStatus::class,
        CryptoRateUpdate::class,
        FiatRateUpdate::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();


        $schedule->command('cron:status')->hourly();
        $schedule->command('crypto-rate:update')->everyThreeMinutes();
        $schedule->command('fiat-rate:update')->everyFourHours();
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
