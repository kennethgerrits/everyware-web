<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Worksheet extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $collection = "worksheets";
    protected $fillable = [
        'template_id',
        'user_id',
        'started_at',
        'ended_at',
        'question_amount',
        'success_amount',
        'question_type',
        'answer_type',
        'min_amount',
        'max_amount',
        'sum_type',
        'cesuur'
    ];
    protected $dates = [
        'started_at',
        'ended_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function getFinishedAttribute()
    {
        return $this->cesuur >= $this->success_amount;
    }
}
