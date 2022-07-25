<?php

namespace App\Models;

use App\Enums\StatisticType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $collection = "templates";

    public $all;
    public $is_new;

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function wordlist()
    {
        return $this->belongsTo(Wordlist::class);
    }

    public function statistic()
    {
        return $this->hasOne(Statistic::class)->where('type', StatisticType::GENERAL);
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function scopeCollection($query)
    {
        return $query->where('is_collection', true);
    }

    public function scopeNormal($query)
    {
        return $query->whereNull('is_collection');
    }

    public function getAverageAttribute()
    {
        $worksheets = Worksheet::where('template_id', $this->id)->get();
        return getAverage($worksheets);
    }

    public function getSucceededAttribute()
    {
        $worksheets = Worksheet::where('template_id', $this->id)
            ->get();
        return getSuccessRate($worksheets);
    }

    public function getTimeSpendAttribute()
    {
        $worksheets = Worksheet::where('template_id', $this->id)
            ->get();
        return getTimeSpend($worksheets);
    }

    public function getIsMathAttribute()
    {
        $mathArray = ["ARITHMETIC_IMAGE", "ARITHMETIC_SUM_TEXT", "ARITHMETIC_SUM_IMAGE", "ARITHMETIC_LISTENING"];
        if (in_array($this->question_type, $mathArray)) {
            return true;
        } else {
            return false;
        }
    }

    public function getTextTagsAttribute()
    {
        $text = "";
        $isFirst = true;
        $ids = $this->tags ?: [];
        $tags = Tag::whereIn('_id', $ids)->get();
        if ($tags->count()) {
            foreach ($tags as $tag) {
                if ($isFirst) {
                    $text .= $tag->name;
                    $isFirst = false;
                } else {
                    $text .= ", " . $tag->name;
                }
            }
        }
        return $text;
    }
}
