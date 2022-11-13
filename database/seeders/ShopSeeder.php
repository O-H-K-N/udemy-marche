<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('shops')->insert([
        [
            'owner_id' => 1,
            'name' => '店舗1',
            'information' => 'オーナー1の店舗の情報です。オーナー1の店舗の情報ですオーナー2の店舗の情報です' ,
            'filename' => "",
            'is_selling' => true,
        ],
        [
            'owner_id' => 2,
            'name' => '店舗2',
            'information' => 'オーナー2の店舗の情報です。オーナー2の店舗の情報ですオーナー1の店舗の情報です' ,
            'filename' => "",
            'is_selling' => true,
        ],
        [
            'owner_id' => 3,
            'name' => '店舗3',
            'information' => 'オーナー3の店舗の情報です。オーナー3の店舗の情報ですオーナー3の店舗の情報です' ,
            'filename' => "",
            'is_selling' => true,
        ],
      ]);
    }
}
