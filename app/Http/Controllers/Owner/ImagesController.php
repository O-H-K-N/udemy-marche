<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use App\Models\Product;
use App\Services\ImageService;

class ImagesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('image');

            if (!is_null($id)) {
              $imagesOwnerId = Image::findOrFail($id)->owner->id;
              $imageId = (int)$imagesOwnerId;
              if ($imageId !== Auth::id()) {
                // 404表示
                abort(404);
              }
            }
            // dd($request->route()->parameter('Image')); //=> 文字列
            // dd(Auth::id()); //=> 数字

            return $next($request);
        });
    }

    public function index()
    {
        $images = Image::where('owner_id', Auth::id())
        ->orderBy('updated_at', 'desc')
        ->paginate(20);

        return view('owner.images.index', compact('images'));
    }

    public function create()
    {
        return view('owner.images.create');
    }

    public function store(Request $request)
    {
        $imageFiles = $request->file('files');
        if(!is_null($imageFiles)) {
            foreach($imageFiles as $imageFile) {
                $fileNameToStore = ImageService::upload($imageFile, 'products');
                Image::create([
                  'owner_id' => Auth::id(),
                  'filename' => $fileNameToStore
                ]);
            }
        }

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像を登録しました。',
        'status' => 'info']);
    }

    public function edit($id)
    {
        $image = Image::findOrFail($id);

        return view('owner.images.edit', compact('image'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['string', 'max:50'],
        ]);

        $Image = Image::findOrFail($id);
        $Image->title = $request->title;

        $Image->save();

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像情報を更新しました。',
        'status' => 'info']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        // Storageにある画像ファイルのパスを取得
        $filePath = 'public/products/' . $image->filename;

        // 削除する画像が使われている商品を取得
        $imageInProducts = Product::where('image1', $image->id)
        ->orWhere('image2', $image->$id)
        ->orWhere('image3', $image->$id)
        ->orWhere('image4', $image->$id)
        ->get();

        // 削除する画像が使われてる商品の画像カラムをnullにする
        if ($imageInProducts) {
          $imageInProducts->each(function($product) use($image) {
            if($product->image1 === $image->id) {
              $product->image1 = null;
              $product->save();
            }
            if($product->image2 === $image->id) {
              $product->image2 = null;
              $product->save();
            }
            if($product->image3 === $image->id) {
              $product->image3 = null;
              $product->save();
            }
            if($product->image4 === $image->id) {
              $product->image4 = null;
              $product->save();
            }
          });
        }

        // パスを指定して削除
        if(Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        Image::findOrFail($id)->delete();

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像を削除しました', 'status' => 'alert']);
    }
}
