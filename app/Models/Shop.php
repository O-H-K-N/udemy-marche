<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    // 店舗を所有しているオーナーを取得
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
