<?php

namespace App\Jobs;

use App\Enums\StatisticType;
use App\Models\Statistic;
use App\Models\Template;
use App\Models\User;
use App\Models\Worksheet;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateUserStatistics implements ShouldQueue
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
        $users = User::all();
        $templates = Template::all();

        foreach ($templates as $template) {

            foreach ($users as $user) {
                $worksheets = Worksheet::where('template_id', $template->id)->where('user_id', $user->id)->get();

                if($worksheets->count() == 0){
                    continue;
                }

                $statistic = Statistic::firstOrNew(['template_id' => $template->id, 'user_id' => $user->id, 'type' => StatisticType::DETAIL]);
                $statistic->average = getAverage($worksheets);
                $statistic->average_time_spend = getTimeSpend($worksheets);
                $statistic->success_rate = getSuccessRate($worksheets);
                $statistic->attempts = $worksheets->count();
                $statistic->latest_refresh = Carbon::now()->toDateTimeString();
                $statistic->save();
            }
        }
    }
}
