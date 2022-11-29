<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Image;
use App\Models\SecondaryCategory;
use App\Models\Owner;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('product');

            if (!is_null($id)) {
              $productOwnerId = Product::findOrFail($id)->shop->owner->id;
              $productId = (int)$productOwnerId;
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
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
