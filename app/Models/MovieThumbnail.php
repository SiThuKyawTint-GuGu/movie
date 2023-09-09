<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieThumbnail extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id','image'
    ];

}
