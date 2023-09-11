<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
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
                'name'       => "Action",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Romance",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Adventure",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Drama",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Science",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Genre::insert($data);
    }
}
