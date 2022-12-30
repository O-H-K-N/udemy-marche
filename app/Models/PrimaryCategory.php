<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryCategory extends Model
{
    use HasFactory;

    // 第一カテゴリが所有している第二カテゴリを取得
    public function secondary()
    {
        return $this->hasMany(SecondaryCategory::class);
    }
}
