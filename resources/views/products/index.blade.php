@extends('layouts.app')

@section('title', '商品管理')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">商品管理</h1>
        <p class="page-subtitle">商品の登録・編集・管理</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規登録
    </a>
</div>

{{-- 検索フォーム --}}
<div class="search-box">
    <form method="GET" action="{{ route('products.index') }}" class="search-form">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="商品名・コードで検索"
               class="search-input">
        <select name="category_id" class="search-select">
            <option value="">全カテゴリ</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-dark">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            検索
        </button>
        @if(request('search') || request('category_id'))
            <a href="{{ route('products.index') }}" class="btn btn-secondary">クリア</a>
        @endif
    </form>
</div>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>商品コード</th>
                <th>商品名</th>
                <th>カテゴリ</th>
                <th class="col-right">標準単価</th>
                <th class="col-center">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td class="td-code">{{ $product->code }}</td>
                <td class="td-name">
                    <a href="{{ route('products.show', $product) }}" class="td-link">
                        {{ $product->name }}
                    </a>
                </td>
                <td>
                    @if($product->category)
                        <span class="badge badge-secondary">{{ $product->category->name }}</span>
                    @else
                        <span>—</span>
                    @endif
                </td>
                <td class="col-right td-price">¥{{ number_format($product->unit_price) }}</td>
                <td class="col-center">
                    <div class="td-actions">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-edit btn-sm">編集</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}"
                              onsubmit="return confirm('「{{ $product->name }}」を削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete btn-sm">削除</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="table-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    商品が登録されていません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-pagination">
    {{ $products->links() }}
</div>
@endsection
