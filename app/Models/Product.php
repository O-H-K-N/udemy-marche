<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // 商品を所有している店舗を取得
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // 商品が属しているカテゴリを取得
    public function category()
    {
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    // 商品の一枚目の画像を取得
    public function imageFirst()
    {
        return $this->belongsTo(Image::class, 'image1', 'id');
    }

}
