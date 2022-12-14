<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
      'owner_id',
      'name',
      'information',
      'filename',
      'is_selling',
    ];

    // 店舗を所有しているオーナーを取得
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    // 店舗が所有している商品を取得
    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
