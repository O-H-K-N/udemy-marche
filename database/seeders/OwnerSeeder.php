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
                'name' => 'owner1',
                'email' => 'owner1@owner1.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
            [
                'name' => 'owner2',
                'email' => 'owner2@owner2.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
            [
                'name' => 'owner3',
                'email' => 'owner3@owner3.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
            [
                'name' => 'owner4',
                'email' => 'owner4@owner4.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
            [
                'name' => 'owner5',
                'email' => 'owner5@owner5.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
            [
                'name' => 'owner6',
                'email' => 'owner6@owner6.com',
                'password' => Hash::make('password'),
                'created_at' => '2021/01/01 11:11:11'
            ],
        ]);
    }
}
