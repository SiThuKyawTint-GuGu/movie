<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Genre;
use App\Models\Author;
use App\Enums\MoviePublishStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','summary','cover_image','imdb_ratings','pdf_download_link','publish_status'
    ];

    protected $casts = [
        'publish_status' => MoviePublishStatusEnum::class
    ];

    public function authors(){
        return $this->belongsToMany(Author::class,'movie_author_pivots','movie_id', 'id');
    }

    public function genres(){
        return $this->belongsToMany(Genre::class,'movie_genre_pivots','movie_id', 'id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class,'movie_tag_pivots','movie_id', 'id');
    }


}
