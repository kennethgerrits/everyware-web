<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Difficulty extends Model
{
    use HasFactory;
    protected $collection = "difficulties";
    protected $fillable = [
        'name'
    ];
}