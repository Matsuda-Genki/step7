@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2>商品情報編集画面</h2></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="product_name" class="form-label">商品名<span class="text-danger">*</span></label>
                            <input id="product_name" type="text" name="product_name" class="form-control" value="{{ old('product_name', $product -> product_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="company_id" class="form-label">メーカー名<span class="text-danger">*</span></label>
                            <select class="form-select" id="company_id" name="company_id" class="form-control">
                                @foreach ($companies as $company)
                                    @if($company -> id == $product -> company -> id)
                                        <option value="{{ $company -> id }}" selected>{{ $company -> company_name }}</option>
                                    @else
                                        <option value="{{ $company -> id }}">{{ $company -> company_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">価格<span class="text-danger">*</span></label>
                            <input id="price" type="number" name="price" class="form-control" value="{{ old('price', $product -> price) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">在庫数<span class="text-danger">*</span></label>
                            <input id="stock" type="number" name="stock" class="form-control" value="{{ old('stock', $product -> stock) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">コメント</label>
                            <textarea id="comment" name="comment" class="form-control" rows="3">{{ old('comment', $product -> comment) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="img_path" class="form-label">商品画像</label>
                            <input id="img_path" type="file" name="img_path" class="form-control">
                        </div>
                            <div class="text-center"><img src="{{ asset($product->img_path) }}" height="300"></div>
                        <button type="submit" class="btn btn-warning mb-3">更新</button>
                        <a href="{{ url() -> previous() }}" class="btn btn-primary mb-3">戻る</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
