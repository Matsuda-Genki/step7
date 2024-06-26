<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ProductController extends Controller
{
    // 商品一覧
    public function index(Request $request) {
        // 全ての商品情報取得
        $query = Product::query();

         // 商品名の検索キーワードがある場合、そのキーワードを含む商品をクエリに追加
        if ($search = $request->search){
            $query->where('product_name', 'LIKE', "%{$search}%");
        }

        // メーカー名が指定されている場合、その商品をクエリに追加
        if ($company_id = $request->company_id){
            $query->where('company_id', '=', $company_id);
        } else {
            $products = Product::all(); 
        }
        // 上記の条件(クエリ）に基づいて商品を取得し、10件ごとのページネーションを適用
        $products = $query->paginate(10);

        // 商品一覧ビューを表示し、取得した商品情報をビューに渡す
        return view('products.index', ['products' => $products]);
    }

    // 商品作成
    public function create() {
        try {
            // 全ての会社情報取得
            $companies = Company::all();

            return view('products.create', compact('companies'));

        } catch (Exception $e) {
            // エラー内容表示
            Log::debug($e->getMessage());
        }
    }

    // 商品保存
    public function store(Request $request) {
        try {
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

        } catch (Exception $e) {
            // エラー内容表示
            Log::debug($e->getMessage());
        }
    }

    // 商品詳細表示
    public function show(Product $product) {

        return view('products.show',  ['product' => $product]);
    }

    // 商品編集
    public function edit(Product $product) {
        try {
            // 全ての会社情報取得
            $companies = Company::all();

            // 商品編集画面表示
            return view('products.edit', compact('product', 'companies'));

        } catch (Exception $e) {
            // エラー内容表示
            Log::debug($e->getMessage());
        }
    }

    public function update(Request $request, Product $product) {
        try {
            // バリデーション
            $request -> validate([
                'product_name' => 'required',
                'price' => 'required',
                'stock' => 'required',
            ]);

            // 商品情報更新
            $product -> product_name = $request -> product_name;
            $product -> company_id = $request -> company_id;
            $product -> price = $request -> price;
            $product -> stock = $request -> stock;
            $product -> comment = $request -> comment;

            // もし画像があるなら保存
            if ($request -> hasFile('img_path')) {
                $file_name = $request -> img_path -> getClientOriginalName();
                $file_path = $request -> img_path -> storeAs('products', $file_name, 'public');
                $product -> img_path = '/storage/' . $file_path;
            }

            // 更新保存
            $product -> save();

            return redirect() -> route('products.index') -> with('success', 'Product updated successfully');

        } catch (Exception $e) {
            // エラー内容表示
            Log::debug($e->getMessage());
        }
    }

    // 商品削除
    public function destroy(Product $product) {
        try {
            $product -> delete();

            return redirect('/products');

        } catch (Exception $e) {
            // エラー内容表示
            Log::debug($e->getMessage());
        }
    }

    // 商品検索
    public function search(Request $request){
    $company = new Company;
    $companies = $company->getLists();

    // 商品編集画面表示
    return view('products.index', compact('product', 'companies'));
    }

}
