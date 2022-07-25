<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use App\Enums\WordlistType;

class Wordlist extends Model
{
    use HasFactory;

    protected $collection = "wordlists";


    public function getImagesAttribute()
    {
        if ($this->list && count($this->list)) {
            $images = Image::whereIn('_id', $this->list)->get();
            return $images;
        }
        return collect();
    }

    public function getTypeTextAttribute()
    {
        return WordlistType::KEYS[$this->type];
    }

}
