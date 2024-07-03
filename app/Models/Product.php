<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{   

    // ダミーデータ挿入用
    use HasFactory;

    // テーブルにデータを保存・変更するメソッド
    protected $fillable = [
        'product_name',
        'price',
        'stock',
        'company_id',
        'comment',
        'img_path',
    ];

    // Productモデル(1)⇔sales(多)テーブルのリレーションメソッド
    public function sales() {
        return $this -> hasMany(sale::class);
    }

    // Productモデル(多)⇔companies(1)テーブルのリレーションメソッド
    public function company() {
        return $this -> belongsTo(Company::class);
    }
}
