<?php

namespace App\Http\Controllers;


use App\Forms\ClassGroupForm;
use App\Models\ClassGroup;
use App\Models\Template;
use App\Models\User;
use App\Models\Worksheet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $indexView = 'dashboard.index';

    public function index()
    {
        $view = view($this->indexView);

        $hour = Date('H', time());
        $day_term = ($hour > 17) ? __('general.evening') : (($hour >= 12) ? __('general.afternoon') : __('general.morning'));
        $logged_in_user = auth()->user();

        $worksheets = collect();
        $teachers = collect();
        $texts = [];

        if ($logged_in_user->isTeacher()) {
            // Start recent activities from teacher classes.
            $classgroups = ClassGroup::whereIn('_id', $logged_in_user->teachergroup_ids)->pluck('student_ids');
            $user_ids = [];
            if ($classgroups->count()) {

                foreach ($classgroups as $classgroup) {
                    $user_ids += $classgroup;
                }
                $user_ids = array_unique($user_ids);
            }

            $texts = [];

            foreach ($user_ids as $user_id) {
                $worksheetsUser = Worksheet::select('_id', 'user_id', 'template_id', 'cesuur', 'success_amount', 'ended_at')
                ->where('user_id', $user_id)
                ->whereNotNull('template_id')
                ->where('ended_at', '>=', Carbon::now()->subDays(2))
                ->orderBy('ended_at', 'desc')
                ->get();

                if ($worksheetsUser->count() > 0) {
                    $worksheetsUser = $worksheetsUser->filter(function ($item) {
                        return $item->success_amount >= $item->cesuur;
                    });

                    $text =  $worksheetsUser->first()->ended_at . ' - ' . $worksheetsUser->first()->user->getNameAttribute() . ' heeft: ' . $worksheetsUser->count() . ' werkbladen afgerond';
                    array_push($texts, $text);
                }
            }
            // End recent activities from teacher classes.
            $departments = auth()->user()->departments ?: [];

            $all_teachers = [];

            foreach ($departments as $department) {
                $tempTeachers = User::where('departments', 'all', [$department])->where('_id', '!=', auth()->user()->id)->pluck('_id')->toArray();
                $all_teachers = array_merge($all_teachers, $tempTeachers);
            }

            $all_teachers = array_unique($all_teachers);

            $teachers = User::findMany($all_teachers);
        } else if ($logged_in_user->isAdmin()) {
            // Start recent activities from all classes.
            $classgroups = ClassGroup::all()->pluck('student_ids');
            $user_ids = [];
            if ($classgroups->count()) {

                foreach ($classgroups as $classgroup) {
                    $user_ids += $classgroup;
                }
                $user_ids = array_unique($user_ids);
            }

            $texts= [];

            foreach($user_ids as $user_id){
                $worksheetsUser= Worksheet::select('_id', 'user_id', 'template_id', 'cesuur', 'success_amount', 'ended_at')
                ->where('user_id', $user_id)
                    ->whereNotNull('template_id')
                    ->where('ended_at', '>=', Carbon::now()->subDays(2))
                    ->orderBy('ended_at', 'desc')
                    ->get();

                if($worksheetsUser->count() > 0){
                    $worksheetsUser = $worksheetsUser->filter(function ($item) {
                        return $item->success_amount >= $item->cesuur;
                    });

                    $text =  $worksheetsUser->first()->ended_at . ' - ' . $worksheetsUser->first()->user->getNameAttribute() . ' heeft: ' . $worksheetsUser->count() . ' werkbladen afgerond';
                    array_push($texts, $text);
                }
            }

            $teachers = User::teacher()->get();
        }

        // Start recent activities in worksheets.
        $recently_edited_worksheets = Template::select('_id', 'name', 'created_at', 'updated_at', 'edited_by')
            ->whereNotNull('updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        // End recent activities in worksheets.

        $view->day_term = $day_term;
        $view->logged_in_user = $logged_in_user;
        $view->worksheets = $worksheets;
        $view->recently_edited_worksheets = $recently_edited_worksheets;
        $view->teachers = $teachers;
        $view->texts = $texts;
        return $view;
    }
}
