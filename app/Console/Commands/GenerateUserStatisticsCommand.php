<?php

namespace App\Console\Commands;

use App\Enums\StatisticType;
use App\Jobs\GenerateUserStatistics;
use App\Models\Statistic;
use App\Models\Template;
use App\Models\Worksheet;
use App\Models\User;

use Illuminate\Console\Command;

class GenerateUserStatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:generate:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate statistics for all templates per user';

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
     *
     */
    public function handle()
    {
        GenerateUserStatistics::dispatch();
    }
}
