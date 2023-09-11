<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'title'             => "fight",
                'summary'           => "about fight",
                'cover_image'       => "",
                'imdb_ratings'      => "8",
                'pdf_download_link' => "www.google.com",
                'publish_status'    => "1",
                'created_at'        => now(),
                'updated_at'        => now()
            ],
            [
                'title'             => "love",
                'summary'           => "about love",
                'cover_image'       => "",
                'imdb_ratings'      => "9",
                'pdf_download_link' => "www.youtube.com",
                'publish_status'    => "1",
                'created_at'        => now(),
                'updated_at'        => now()
            ],
        ];
        foreach ($data as $movieData) {
            $movie = Movie::create($movieData);
            $movie->genres()->attach([1, 2]); 
            $movie->tags()->attach([1, 2]);
            $movie->authors()->attach([1, 2]); 
        }
    }
}
