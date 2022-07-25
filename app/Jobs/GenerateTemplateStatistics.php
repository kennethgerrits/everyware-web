<?php

namespace App\Jobs;

use App\Enums\StatisticType;
use App\Models\Statistic;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateTemplateStatistics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $templates = Template::all();
        foreach ($templates as $template){
            $statistic = Statistic::firstOrNew(['template_id' => $template->id, 'type' => StatisticType::GENERAL]);
            $statistic->average = $template->average;
            $statistic->success_rate = $template->succeeded;
            $statistic->average_time_spend = $template->time_spend;
            $statistic->latest_refresh = Carbon::now()->toDateTimeString();
            $statistic->save();
        }
    }
}
