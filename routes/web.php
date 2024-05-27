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
    Route::resource('products', ProductController::class);
});
