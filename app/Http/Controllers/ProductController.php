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
    public function index() {
        // 商品一覧
        $companies = Company::all();
        return view('products.index', compact('companies'));

    }

    public function search(Request $request) {
        // 全ての商品情報取得
        $query = Product::with('company');

         // 商品名の検索ワードがある場合、そのワードを含む商品をクエリに追加
        if ($request->filled('product_name')) {
            $query->where('product_name', 'LIKE', '%' . $request->product_name . '%');
        }

        // メーカー名が指定されている場合、その商品をクエリに追加
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // 最小価格の設定がある場合、その価格以下の商品をクエリに追加
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // 最大価格の設定がある場合、その価格以上の商品をクエリに追加
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 最小在庫の設定がある場合、その在庫以下の商品をクエリに追加
        if ($request->filled('min_stock')) {
            $query->where('stock', '>=', $request->min_stock);
        }

        // 最大在庫の設定がある場合、その在庫以上の商品をクエリに追加
        if ($request->filled('max_stock')) {
            $query->where('stock', '<=', $request->max_stock);
        }

        // ソートのパラメータが指定されている場合、そのカラムでソートを行う
        if ($request->filled('sort')) {
            $sortColumn = $request->sort;
            $sortOrder = $request->order ?? 'asc';
            if ($sortColumn == 'company_name') {
                $query->join('companies', 'products.company_id', '=', 'companies.id')

                ->orderBy('companies.company_name', $sortOrder);
            } else {
                $query->orderBy($sortColumn, $sortOrder);
            }
        }

        $products = $query->get();

        // 取得した商品情報をjsonで渡す
        return response()->json($products->map(function($product) {
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'company' => $product->company,
                'price' => $product->price,
                'stock' => $product->stock,
                'img_path' => $product->img_path
            ];
        }));
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
    public function destroy($id) {
        try {
            $product = Product::findOrFail($id);
            $product -> delete();

            return response()->json(['message' => '商品を削除しました']);

        } catch (Exception $e) {
            // エラー内容表示
            Log::debug($e->getMessage());
        }
    }
}
