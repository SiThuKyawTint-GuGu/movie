<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AuthorSeeder extends Seeder
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
                'name'       => "Author 1",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Author 2",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Author 3",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Author 4",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Author 5",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Author::insert($data);
    }
}
