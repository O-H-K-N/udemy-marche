<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreOwnerRequest;
// 日付ライブラリ
use Carbon\Carbon;

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
        ->paginate(3);

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
        // フォームの内容を$requestに格納

        Owner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

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
