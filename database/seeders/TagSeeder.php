<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagSeeder extends Seeder
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
                'name'       => "Tag 1",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Tag 2",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Tag 3",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Tag 4",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => "Tag 5",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Tag::insert($data);
    }
}
