<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // ログイン済
    if (Auth::check()) {    
        return redirect() -> route('products.index');

    // 未ログイン
    } else {
        return redirect() -> route('login');
    }
});

// 認証画面
Auth::routes();

Route::group(['middleware' => 'auth'], function() {

    // 一覧画面の表示
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    // 登録画面の表示
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    // 登録処理
    Route::post('/store', [ProductController::class, 'store'])->name('products.store');
    // 商品詳細
    Route::get('/show/{product}', [ProductController::class, 'show'])->name('products.show');
    // 編集画面
    Route::get('/edit/{product}', [ProductController::class, 'edit'])->name('products.edit');
    // 更新処理
    Route::post('/update/{product}', [ProductController::class, 'update'])->name('products.update');
    // 商品削除
    Route::post('/destroy{product}', [ProductController::class, 'destroy'])->name('products.destroy');

});