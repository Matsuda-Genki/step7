<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 商品一覧
    public function index() {
        // 全ての商品情報取得
        $products = product::all();
        
        // 商品一覧表示
        return view('products.index', compact('products'));
    }

    // 商品作成
    public function create() {
        // 全ての会社情報取得
        $companies = Company::all();

        return view('products.create', compact('companies'));
    }

    // 商品保存
    public function store(Request $request) {
        // バリデーション
        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'company_id' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable | image',
        ]);

        // 商品インスタンス生成
        $product = new Product([
            'product_name' => $request -> get('product_name'),
            'price' => $request -> get('price'),
            'stock' => $request -> get('stock'),
            'company_id' => $request ->get('company_id'),
            'comment' => $request -> get('comment'),
        ]);

        // もし画像があるなら保存
        if ($request -> hasFile('img_path')) {
            $file_name = $request -> img_path -> getClientOriginalName();
            $file_path = $request -> img_path -> storeAs('products', $file_name, 'public');
            $product -> img_path = '/storage/' . $file_path;
        }

        // データベースに保存
        $product -> save();

        return redirect('products');
    }

    // 商品詳細表示
    public function show(Product $product) {
        return view('products.show', ['product' => $product]);
    }

    // 商品編集
    public function edit(Product $product) {
        // 全ての会社情報取得
        $companies = Company::all();

        // 商品編集画面表示
        return view('products.edit', compact('product', 'companies'));
    }

    public function update(Request $request, Product $product) {
        // バリデーション
        $request -> validate([
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        // 商品情報更新
        $product -> product_name = $request -> product_name;
        $product -> price = $request -> price;
        $product -> stock = $request -> stock;

        // 更新保存
        $product -> save();

        return redirect() -> route('products.index') -> with('success', 'Product updated successfully');
    }

    // 商品削除
    public function destroy(Product $product) {
        $product -> delete();

        return redirect('/products');
    }

    // 商品検索

}
