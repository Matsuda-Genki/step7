<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class SalesController extends Controller
{
    public function purchase(Request $request) {
        // リクエストからデータ取得
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // データベースから商品を検索・取得
        $product = Product::find($productId);

        // 商品が存在しない、または在庫が不足している場合のバリデーション
        if (!$product) {
            return response()->json(['message' => '商品が存在しません'], 404);
        }
        if ($product->stock < $quantity) {
            return response()->json(['message' => '商品の在庫が不足しています'], 400);
        }

        // 在庫を減少させる
        $product->stock -= $quantity;
        $product->save();

        // Salesテーブルに商品IDと購入日時を記録する
        $sale = new Sale;
        $sale->product_id = $productId;
        $sale->save();

        return response()->json(['message' => '購入成功']);
    }
}
