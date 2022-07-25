<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Statistic extends BaseModel
{
    use HasFactory;

    protected $collection = "statistics";

    public $timestamps = false;

    protected $fillable = [
        'template_id',
        'user_id',
        'type',
        'latest_refresh'
    ];
}
