<?php

namespace App\Http\Controllers;


use App\Jobs\GenerateTemplateStatistics;
use App\Jobs\GenerateUserStatistics;
use App\Models\Template;
use App\Models\User;
use App\Models\Worksheet;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        $view = view('stats.index');
        $templates = Template::normal()->get();
        $templates = $templates->filter(function ($template){
            return $template->statistic != null;
        });
        $view->templates = $templates;
        return $view;
    }

    public function users($template_id)
    {
        $user_ids = Worksheet::where('template_id', $template_id)->groupBy('user_id')->pluck('user_id');
        $users = User::findMany($user_ids);
        $template = Template::find($template_id);
        $view = view('stats.users');
        $view->users = $users;
        $view->template = $template;
        return $view;
    }

    public function worksheets($template_id, $user_id)
    {
        $worksheets = Worksheet::where('template_id', $template_id)->where('user_id', $user_id)->orderBy('ended_at', 'DESC')->get();
        $user = User::find($user_id);
        $template = Template::find($template_id);

        $view = view('stats.worksheets');

        $view->user = $user;
        $view->worksheets = $worksheets;
        $view->template = $template;

        return $view;
    }

    public function worksheetsDetails($template_id, $user_id, $worksheet_id)
    {
        $worksheet = Worksheet::find($worksheet_id);

        $view = view('stats.partial.worksheet');

        $view->worksheet = $worksheet;

        return $view;
    }

    public function refreshStats(){
        GenerateTemplateStatistics::dispatch();
        GenerateUserStatistics::dispatch();
    }
}
