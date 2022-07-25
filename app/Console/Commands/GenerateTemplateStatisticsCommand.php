<?php

namespace App\Console\Commands;

use App\Enums\StatisticType;
use App\Jobs\GenerateTemplateStatistics;
use App\Models\Statistic;
use App\Models\Template;
use Illuminate\Console\Command;

class GenerateTemplateStatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:generate:templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate statistics for all templates';

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
        GenerateTemplateStatistics::dispatch();
    }
}
