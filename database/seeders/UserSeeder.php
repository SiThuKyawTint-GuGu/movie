<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
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
                'name'                  => "gugu",
                'email'                 => "gugu008@gmail.com",
                'password'              => "guguadmin",
                'image'                 => "",
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
        ];
        User::insert($data);
    }
}
