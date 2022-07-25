<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $collection = 'classrooms';
}
