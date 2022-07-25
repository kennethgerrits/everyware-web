<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $logged_in_user = auth()->user();
        $view = view('groups');

        if ($logged_in_user->isTeacher()) {
            $classgroups = ClassGroup::whereIn('_id', $logged_in_user->teachergroup_ids)->get();
            $users = User::student()->get();
        } else if ($logged_in_user->isAdmin()) {
            $classgroups = ClassGroup::all();
            $users = User::all();
        }

        $view->users = $users;
        $view->classgroups = $classgroups;

        return $view;
    }
}
