@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mx-auto col-md-8 card">
        <h1 class="card-header mb-4">商品詳細画面</h1>

        <div class="card-body">
            <dl class="row mt-3">
                <dt class="col-sm-3">商品ID</dt>
                <dd class="col-sm-9">{{ $product->id }}</dd>

                <dt class="col-sm-3">商品名</dt>
                <dd class="col-sm-9">{{ $product->product_name }}</dd>

                <dt class="col-sm-3">メーカー名</dt>        
                <dd class="col-sm-9">{{ $product->company->company_name }}</dd>

                <dt class="col-sm-3">価格</dt>        
                <dd class="col-sm-9">{{ $product->price }}</dd>

                <dt class="col-sm-3">在庫数</dt>        
                <dd class="col-sm-9">{{ $product->stock }}</dd>

                <dt class="col-sm-3">コメント</dt>        
                <dd class="col-sm-9">{{ $product->comment }}</dd>

                <dt class="col-sm-3">商品画像</dt>        
                <dd class="col-sm-9"><img src="{{ asset($product->img_path) }}" height="300"></dd>
            </dl>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning mb-3">編集</a>
            <a href="{{ route('products.index') }}" class="btn btn-primary mb-3">戻る</a>
        </div>
    </div>
</div>
@endsection
