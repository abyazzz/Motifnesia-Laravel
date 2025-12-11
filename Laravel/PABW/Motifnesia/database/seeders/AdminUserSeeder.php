<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'full_name' => 'Admin Motifnesia',
            'email' => 'admin@motifnesia.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        DB::table('users')->insert([
            'name' => 'abay',
            'full_name' => 'Muhammad Abyaz Zaydan',
            'email' => 'abay@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'customer',
        ]);
    }
}
