<?php

namespace App\Observers;

use App\Models\ClassGroup;
use App\Models\Template;
use App\Models\User;
use App\Models\Worksheet;

class UserObserver
{
    public function deleted(User $user)
    {
        //Check if any classgroup has this user.
        if($user->isTeacher()){
            $classGroups = ClassGroup::where('teacher_ids', 'all', [$user->id])->get();
            if($classGroups->count()){
                foreach ($classGroups as $classGroup){
                    $classGroup->pull('teacher_ids', $user->id);
                }
            }
        }elseif($user->isStudent()){
            $classGroups = ClassGroup::where('student_ids', 'all', [$user->id])->get();
            if($classGroups->count()){
                foreach ($classGroups as $classGroup){
                    $classGroup->pull('student_ids', $user->id);
                }
            }
        }


        //Delete worksheets created by the user
        Worksheet::where('user_id', $user->id)->delete();


    }
}
