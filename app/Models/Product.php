<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
      'shop_id',
      'name',
      'information',
      'price',
      'is_selling',
      'sort_order',
      'secondary_category_id',
      'image1',
      'image2',
      'image3',
      'image4'
    ];

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

    // 商品の二枚目の画像を取得
    public function imageSecond()
    {
        return $this->belongsTo(Image::class, 'image2', 'id');
    }

    // 商品の三枚目の画像を取得
    public function imageThird()
    {
        return $this->belongsTo(Image::class, 'image3', 'id');
    }

    // 商品の四枚目の画像を取得
    public function imageFourth()
    {
        return $this->belongsTo(Image::class, 'image4', 'id');
    }

    // 商品の在庫・履歴を取得
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
