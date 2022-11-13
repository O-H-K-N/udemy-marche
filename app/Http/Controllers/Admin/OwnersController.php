<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreOwnerRequest;
use Carbon\Carbon; //=> 日付ライブラリ
use Throwable; //=> throw 文でスロー可能なあらゆるオブジェクトが実装する基底インターフェイス

class OwnersController extends Controller
{
    public function __construct()
    {
        // 認証チェック
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $owners = Owner::select('id', 'name', 'email', 'created_at')
        ->paginate(10);

        return view('admin.owners.index', compact('owners'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOwnerRequest $request)
    {

        // 例外処理
        try{
            // トランザクション
            DB::transaction(function () use($request) {
                // オーナーの作成
                $owner = Owner::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // オーナーに紐づく店舗の作成
                Shop::create([
                    'owner_id' => $owner->id,
                    'name' => '店名を入力してください',
                    'information' => '',
                    'filename' => '',
                    'is_selling' => true,
                ]);
            }, 2);
        }catch(Throwable $e){
            Log::error($e);
            throw $e;
        }

        // withでセッションメッセージ設定
        return redirect()
        ->route('admin.owners.index')
        ->with(['message' => 'オーナー情報を登録しました', 'status' => 'info']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $owner = Owner::findOrFail($id);
        // dd($owner);
        return view('admin.owners.edit', compact('owner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOwnerRequest $request, $id)
    {
        $owner = Owner::findOrFail($id);

        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
        ->route('admin.owners.index')
        ->with(['message' => 'オーナー情報を更新しました', 'status' => 'info']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // ソフトデリート
        Owner::findOrFail($id)->delete();

        return redirect()
        ->route('admin.owners.index')
        ->with(['message' => 'オーナー情報を削除しました', 'status' => 'alert']);
    }

    // 期限切れの(ソフトデリートされた)オーナーを取得
    public function expiredOwnerIndex(){
        $expiredOwners = Owner::onlyTrashed()->get();
        return view('admin.expired-owners', compact('expiredOwners'));
    }

    // ソフトデリートされたオーナーを完全削除
    public function expiredOwnerDestroy($id){
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()
        ->route('admin.expired-owners.index')
        ->with(['message' => 'オーナー情報を完全削除しました', 'status' => 'alert']);
    }
}
