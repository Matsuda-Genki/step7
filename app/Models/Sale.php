<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    // ダミーデータ挿入用
    use HasFactory;

    // Saleモデル(多)⇔companies(1)テーブルのリレーションメソッド
    public function product() {
        return $this -> belogsTo(product::class);
    }
}
