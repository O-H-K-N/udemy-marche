<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// ソフトデリート処理を読み込み
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Authenticatable
{
    // Ownerはソフトデリートとして扱われるようになる
    use HasFactory, SoftDeletes;

    // オーナーが所有している店舗を取得
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    // オーナーが所有している画像を取得
    public function image()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
