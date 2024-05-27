<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // ダミーデータ挿入用
    use HasFactory;

    public function getLists()
    {
        $companies = Company::pluck('company_name', 'id');

        return $companies;
    }

    // Companyモデル(1)⇔products(多)テーブルのリレーションメソッド
    public function products()
    {
        return $this -> hasMany(Product::class);
    }

}
