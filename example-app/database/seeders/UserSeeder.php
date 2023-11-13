<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'dima',
            'last_name' => 'vasiliev',
            'email' => 'user1@test.ru',
            'password' => 'Qa1',
        ]);

        DB::table('users')->insert([
            'first_name' => 'dima',
            'last_name' => 'vasiliev',
            'email' => 'user2@test.ru',
            'password' => 'As2',
        ]);
    }
}
