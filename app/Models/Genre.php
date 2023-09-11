<?php

namespace App\Models;

use App\Casts\Image;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','image'
    ];

    protected $casts    = [
        'image' => Image::class,
    ];


    public function movies(){
        return $this->belongsToMany(Movie::class,'movie_genre_pivots','genre_id', 'id');
    }
}
