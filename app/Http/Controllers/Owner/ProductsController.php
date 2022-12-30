<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\Product;
use App\Models\Image;
use App\Models\Stock;
use App\Models\Shop;
use App\Models\PrimaryCategory;
use App\Models\Owner;
use App\Http\Requests\ProductRequest;

class ProductsController extends Controller
{

    public function __construct()
    {
        // 認証チェック
        $this->middleware('auth:owners');

        // ログイン中のオーナー情報以外を閲覧できないようにする処理
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('product');

            if (!is_null($id)) {
              $productOwnerId = Product::findOrFail($id)->shop->owner->id;
              // 文字列型から数字型に変換
              $productId = (int)$productOwnerId;
              // ログイン中のowner_idを取得
              if ($productId !== Auth::id()) {
                abort(404);
              }
            }

            return $next($request);
        });
    }

    public function index()
    {
        // $products = Owner::findOrFail(Auth::id())->shop->product;

        // Eagerロードを実装
        $ownerInfo = Owner::with('shop.product.imageFirst')
        ->where('id', Auth::id())->get();

        return view('owner.products.index', compact('ownerInfo'));
    }

    public function create()
    {
        $shops = Shop::where('owner_id', Auth::id())
                ->select('id', 'name')
                ->get();

        $images = Image::where('owner_id', Auth::id())
                  ->select('id', 'title', 'filename')
                  ->orderBy('updated_at', 'desc')
                  ->get();

        $categories = PrimaryCategory::with('secondary')->get();

        return view('owner.products.create', compact('shops', 'images', 'categories'));
    }

    public function store(ProductRequest $request)
    {
        // 例外処理
        try{
            // 商品と在校を同時登録するためトランザクション
            DB::transaction(function () use($request) {
                // 商品の作成
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'images1' => $request->images1,
                    'images2' => $request->images2,
                    'images3' => $request->images3,
                    'images4' => $request->images4,
                    'is_selling' => $request->is_selling,
                ]);


                // 商品に紐づく在庫情報の作成
                Stock::create([
                    'product_id' => $product->id,
                    'type' => 1,
                    'quantity' => $request->quantity,
                ]);
            }, 2);
        }catch(Throwable $e){
            Log::error($e);
            throw $e;
        }

        // withでセッションメッセージ設定
        return redirect()
        ->route('owner.products.index')
        ->with(['message' => '商品登録しました', 'status' => 'info']);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');

        $shops = Shop::where('owner_id', Auth::id())
                ->select('id', 'name')
                ->get();

        $images = Image::where('owner_id', Auth::id())
                  ->select('id', 'title', 'filename')
                  ->orderBy('updated_at', 'desc')
                  ->get();

        $categories = PrimaryCategory::with('secondary')->get();

        return view('owner.products.edit', compact('product', 'quantity', 'shops', 'images', 'categories'));
    }

    public function update(ProductRequest $request, $id)
    {
        $request->validate([
          'current_quantity' => ['required', 'integer']
        ]);

        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');

        // 商品情報更新中に在校が変更された時の処理
        if ($request->current_quantity != $quantity) {
          $id = $request->route()->parameter('product');
          return redirect()->route('owner.products.edit', [ 'product' => $id ])
          ->with(['message' => '在庫数が変更されています', 'status' => 'alert']);
        } else {
          // 例外処理
          try{
            // 商品と在校を同時更新するためトランザクション
            DB::transaction(function () use($request, $product) {
                $product->name = $request->name;
                $product->information = $request->information;
                $product->price = $request->price;
                $product->sort_order = $request->sort_order;
                $product->shop_id = $request->shop_id;
                $product->secondary_category_id = $request->category;
                $product->image1 = $request->images1;
                $product->image2 = $request->images2;
                $product->image3 = $request->images3;
                $product->image4 = $request->images4;
                $product->is_selling = $request->is_selling;
                $product->save();

              // 在庫の追加or削除を判定
              if ($request->type === \Constant::PRODUCT_LIST['add']) {
                $newQuantity = $request->quantity;
              }
              if ($request->type === \Constant::PRODUCT_LIST['reduce']) {
                $newQuantity = $request->quantity * -1;
              }
              Stock::create([
                  'product_id' => $product->id,
                  'type' => 1,
                  'quantity' => $newQuantity,
              ]);
            }, 2);
          }catch(Throwable $e){
              Log::error($e);
              throw $e;
          }
        }

        // withでセッションメッセージ設定
        return redirect()
        ->route('owner.products.index')
        ->with(['message' => '商品情報を更新しました', 'status' => 'info']);

    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()
        ->route('owner.products.index')
        ->with(['message' => '商品を削除しました', 'status' => 'alert']);
    }
}
