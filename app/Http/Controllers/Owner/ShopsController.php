<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

class ShopsController extends Controller
{
    public function __construct()
    {
        // 認証チェック
        $this->middleware('auth:owners');

        // ログイン中のオーナー情報以外を閲覧できないようにする処理
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('shop');

            // null判定
            if (!is_null($id)) {
              $shopsOwnerId = Shop::findOrFail($id)->owner->id;
              // 文字列型から数字型に変換
              $shopId = (int)$shopsOwnerId;
              // ログイン中のowner_idを取得
              $ownerId = Auth::id();
              if ($shopId !== $ownerId) {
                // 404表示
                abort(404);
              }
            }
            // dd($request->route()->parameter('shop')); //=> 文字列
            // dd(Auth::id()); //=> 数字

            return $next($request);
        });
    }

    public function index()
    {
        $shops = Shop::where('owner_id', Auth::id())->get();

        return view('owner.shops.index', compact('shops'));
    }

    public function edit($id)
    {
        $shop = Shop::findOrFail($id);

        return view('owner.shops.edit', compact('shop'));
    }

    public function update(UploadImageRequest $request, $id)
    {
        // アップロードされた画像を取得
        $imageFile = $request->image;
        // null&アップロード判定
        if (!is_null($imageFile) && $imageFile->isValid() ){
          // サービスを読み取り
          $fileNameToStore = ImageService::upload($imageFile, 'shops');
        }

        return redirect()->route('owner.shops.index');
    }
}
