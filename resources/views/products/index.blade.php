@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品一覧画面</h1>

    <!--検索フォーム-->
    <div class="search mt-5">
    
    <!-- 検索のタイトル -->
        <h2 class="text-center col-4">検索条件で絞り込み</h2>
    
        <form id="searchForm" class="row g-3 text-center">

            <!-- 商品名の入力欄 -->
            <div class="col-6">
                <span class="col-6">商品名称:</span>
                <input class="col-6" type="text" name="product_name">
            </div>

            <!-- メーカー名の選択欄 -->
            <div class="col-6">
                <span class="col-6">メーカー:</span>
                <select class="col-6" name="company_id">
                    <option value=""></option>
                    @foreach($companies as $company)
                    <option value="{{ $company -> id }}">{{ $company -> company_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-6">
                <span class="col-6">最小価格:</span>
                <input class="col-6" type="number" name="min_price">
            </div>

            <div class="col-6">
                <span class="col-6">最大価格:</span>
                <input class="col-6" type="number" name="max_price">
            </div>
            
            <div class="col-6">
                <span class="col-6">最小在庫:</span>
                <input class="col-6" type="number" name="min_stock">
            </div>

            <div class="col-6">
                <span class="col-6">最大在庫:</span>
                <input class="col-6" type="number" name="max_stock">
            </div>

            <div class="col-12 text-end">
                <!-- 絞り込みボタン -->
                <button class="btn btn-outline-secondary col-3" type="submit">検索</button>         

                <!-- 検索条件をリセットするボタン -->
                <button class="btn btn-success col-1" type="button" id="clearForm">クリア</button>
            </div>
            <a href="{{ route('products.create') }}" class="btn btn-warning mb-3">新規登録</a>
        </form>
    </div>

    <!-- 商品一覧表示 -->
    <div class="products mt-5">
        <table class="table table-striped" id="productTable">
            <thead>
                <tr>
                    <th><a href="#" class="sort" data-sort="id">ID</a></th>
                    <th><a href="#" class="sort" data-sort="product_name">商品名</a></th>
                    <th class="">商品画像</th>
                    <th><a href="#" class="sort" data-sort="price">価格</a></th>
                    <th><a href="#" class="sort" data-sort="stock">在庫</a></th>
                    <th><a href="#" class="sort" data-sort="company_name">メーカー名</a></th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <script>
            $(document).ready(function() {
                function fetchProducts(params = {}) {
                    $.ajax({
                        url:"{{ route('products.search') }}",
                        method: 'POST',
                        data: params,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {
                            $('#productTable tbody').empty();

                            data.forEach(function(product) {
                                const imgPath = product.img_path ? `${product.img_path}` : `/default-image.jpg`;

                                $('#productTable tbody').append(
                                    `<tr>
                                        <td>${product.id}</td>
                                        <td>${product.product_name}</td>
                                        <td><img src="${imgPath}" alt="${product.product_name}" style="width:50px; height 100px;"></td>
                                        <td>${product.price}</td>
                                        <td>${product.stock}</td>
                                        <td>${product.company ? product.company.company_name : ''}</td>
                                        <td><a href="/show/${product.id}" class="btn btn-info btn-sm mx-1">詳細表示</a></td>
                                        <td><button class="btn btn-danger btn-sm mx-1 delete-btn" data-id="${product.id}">削除</button></td>
                                    </tr>`
                                );
                            });
                            alert('非同期処理が実行されました');
                        } 
                    });
                }
                
                $('#searchForm').submit(function(e) {
                    e.preventDefault();
                    const params = $(this).serialize();
                    fetchProducts(params);
                });

                $('#clearForm').click(function() {
                    $('#searchForm')[0].reset();
                    fetchProducts();
                });

                $('.sort').click(function(e) {
                    e.preventDefault();
                    const sort = $(this).data('sort');
                    const order = $(this).hasClass('asc') ? 'desc' : 'asc';
                    $(this).toggleClass('asc', order === 'asc').toggleClass('desc', order === 'desc');
             
                    const params = $('#searchForm').serialize() + `&sort=${sort}&order=${order}`;
                    fetchProducts(params);
                });

                $(document).on('click', '.delete-btn', function() {
                    const productId = $(this).data('id');
                    if (confirm('本当に削除しますか？')) {
                        $.ajax({
                            url: `/products/${productId}`,
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(response) {
                                        alert(response.message);
                                        fetchProducts();
                                     }
                        });
                    }
                });

                fetchProducts();

            });
        </script>            
    </div>
</div>    
@endsection
