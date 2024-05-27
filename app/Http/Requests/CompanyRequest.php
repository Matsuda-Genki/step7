<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'company_name' => 'required',
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ];
    }

    /**
     * 項目名
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'company_name' => 'メーカー名',
            'product_name' => '商品名',
            'price' => '価格',
            'stock' => '在庫数',
        ];
    }

    /**
     * エラーメッセージ
     * 
     * @return array
     */
    public function messages() {
        return [
            'company_name.required' => ':attributeは必須項目です。',
            'product_name.required' => ':attributeは必須項目です。',
            'price.required' => ':attributeは必須項目です。',
            'stock.required' => ':attributeは必須項目です。',

        ];
    }

}
