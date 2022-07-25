<?php

namespace App\Console;

use App\Console\Commands\GenerateTemplateStatisticsCommand;
use App\Console\Commands\GenerateUserStatisticsCommand;
use App\Console\Commands\MakeCrudCommand;
use App\Console\Commands\MakeFormCommand;
use App\Console\Commands\MakeMongoModelCommand;
use App\Console\Passport\Console\ClientCommand;
use App\Console\Passport\Console\HashCommand;
use App\Console\Passport\Console\InstallCommand;
use App\Console\Passport\Console\KeysCommand;
use App\Console\Passport\Console\PurgeCommand;
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
        MakeMongoModelCommand::class,
        MakeFormCommand::class,
        MakeCrudCommand::class,
        GenerateTemplateStatisticsCommand::class,
        GenerateUserStatisticsCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('statistics:generate:templates')->hourly();
        $schedule->command('statistics:generate:user')->hourly();
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
