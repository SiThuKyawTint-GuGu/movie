<?php

namespace App\Models;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','image'
    ];

    public function movies(){
        return $this->belongsToMany(Movie::class,'movie_author_pivots','author_id', 'id');
    }
}
