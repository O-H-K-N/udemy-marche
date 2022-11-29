<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryCategory extends Model
{
    use HasFactory;

    // 第二カテゴリを所有している第一カテゴリを取得
    public function primary()
    {
        return $this->belongsTo(PrimaryCategory::class);
    }
}
