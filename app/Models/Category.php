<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $collection = "categories";

    protected $fillable = [
        'name',
        'color',
        'image_id',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
