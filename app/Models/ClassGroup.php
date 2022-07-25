<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class ClassGroup extends BaseModel
{
    use HasFactory;
    protected $collection = "classgroups";
    protected $fillable = [
        'student_ids',
        'teacher_ids'
    ];

    public function students()
    {
        return $this->belongsToMany(User::class, null, 'studentgroup_ids', 'student_ids');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, null, 'teachergroup_ids', 'teacher_ids');
    }
}
