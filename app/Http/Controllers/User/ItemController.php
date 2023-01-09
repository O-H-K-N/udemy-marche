<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
  public function __construct()
  {
    // 認証チェック
    $this->middleware('auth:users');

    // // ログイン中のオーナー情報以外を閲覧できないようにする処理
    // $this->middleware(function ($request, $next) {
    //     $id = $request->route()->parameter('product');

    //     if (!is_null($id)) {
    //       $productOwnerId = Product::findOrFail($id)->shop->owner->id;
    //       // 文字列型から数字型に変換
    //       $productId = (int)$productOwnerId;
    //       // ログイン中のowner_idを取得
    //       if ($productId !== Auth::id()) {
    //         abort(404);
    //       }
    //     }

    //     return $next($request);
    // });
  }

  public function index()
  {

    $stocks = DB::table('t_stocks')
    ->select('product_id',
    DB::raw('sum(quantity) as quantity'))
    // postgresの場合はquantityも含める
    ->groupBy('product_id', 'quantity')
    ->having('quantity', '>=', 1);

    $products = DB::table('products')
    // $stocksをサブクエリとして指定してjoin句で紐付ける
    ->joinSub($stocks, 'stock', function($join){
      $join->on('products.id', '=', 'stock.product_id');
    })
    // shop, categoryもjoin句を使って結びつける
    ->join('shops', 'products.shop_id', '=', 'shops.id')
    ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
    ->join('images as image1', 'products.image1', '=', 'image1.id')
    ->join('images as image2', 'products.image1', '=', 'image2.id')
    ->join('images as image3', 'products.image1', '=', 'image3.id')
    ->join('images as image4', 'products.image1', '=', 'image4.id')
    // where句販売中の商品のみを指定
    ->where('shops.is_selling', true)
    ->where('products.is_selling', true)
    // 欲しい情報だけを抽出p
    ->select('products.id as id', 'products.name as name', 'products.price as price', 'products.sort_order as sort_order', 'products.information', 'secondary_categories.name as category', 'image1.filename as filename')
    ->get();

    return view('user.index', compact('products'));
  }

  public function show($id)
  {
    $product = Product::findOrFail($id);

    return view('user.show', compact('product'));
  }
}
