<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('owners')->insert([
            [
                'name' => 'test1',
                'email' => 'test1@test1.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
            [
                'name' => 'test2',
                'email' => 'test2@test2.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
            [
                'name' => 'test3',
                'email' => 'test3@test3.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
            [
                'name' => 'test4',
                'email' => 'test4@test4.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
            [
                'name' => 'test5',
                'email' => 'test5@test5.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
            [
                'name' => 'test6',
                'email' => 'test6@test6.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
        ]);
    }
}
