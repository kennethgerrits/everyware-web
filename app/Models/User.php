<?php

namespace App\Models;

use App\Enums\Role;
use App\Passport\src\HasApiTokens;
use App\Enums\StatisticType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $collection = "users";

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'roles',
        'templates',
        'classgroup_id',
        'departments'
    ];

    public $token;


    public function getNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function getPlainRolesAttribute()
    {
        $text = "";
        $isFirst = true;
        if (count($this->roles)) {
            foreach ($this->roles as $role) {
                if ($isFirst) {
                    $text .= Role::KEYS[$role];
                    $isFirst = false;
                } else {
                    $text .= ", " . Role::KEYS[$role];
                }
            }
        }
        return $text;
    }

    public function isStudent()
    {
        return in_array(Role::STUDENT, $this->roles);
    }

    public function isTeacher()
    {
        return in_array(Role::TEACHER, $this->roles);
    }

    public function isAdmin()
    {
        return in_array(Role::ADMIN, $this->roles);
    }

    public function scopeTeacher($query)
    {
        return $query->where('roles', 'all', [Role::TEACHER]);
    }

    public function scopeStudent($query)
    {
        return $query->where('roles', 'all', [Role::STUDENT]);
    }

    public function scopeAdmin($query)
    {
        return $query->where('roles', 'all', [Role::ADMIN]);
    }

    public function studentGroups()
    {
        return $this->belongsToMany(ClassGroup::class, null, 'student_ids', 'studentgroup_ids');
    }

    public function teacherGroups()
    {
        return $this->belongsToMany(ClassGroup::class, null, 'teacher_ids', 'teachergroup_ids');
    }


    public function many(BelongsToMany $relation, $items, $opposingField)
    {
        $relation->detach();

        if (!$items) {
            $this->unset($opposingField);
        } else {
            $this->unset($opposingField);
            $relation->attach($items);
        }
    }

    public function statistics()
    {
        return $this->hasMany(Statistic::class)->where('type', StatisticType::DETAIL);
    }
}
