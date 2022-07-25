<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $collection = "images";
    public $timestamps = false;
    protected $fillable = [
        'src',
        'alt',
        'related_to'
    ];

}
