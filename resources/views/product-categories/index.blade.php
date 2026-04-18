@extends('layouts.app')

@section('title', '商品カテゴリ管理')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">商品カテゴリ管理</h1>
        <p class="page-subtitle">商品カテゴリの登録・編集・管理</p>
    </div>
    <a href="{{ route('product-categories.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規登録
    </a>
</div>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>カテゴリ名</th>
                <th class="col-right">商品数</th>
                <th class="col-center">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td class="td-name">{{ $category->name }}</td>
                <td class="col-right">
                    <span class="badge badge-primary">{{ number_format($category->products_count) }} 件</span>
                </td>
                <td class="col-center">
                    <div class="td-actions">
                        <a href="{{ route('product-categories.edit', $category) }}" class="btn btn-edit btn-sm">編集</a>
                        <form method="POST" action="{{ route('product-categories.destroy', $category) }}"
                              onsubmit="return confirm('「{{ $category->name }}」を削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete btn-sm">削除</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="table-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    カテゴリが登録されていません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-pagination">
    {{ $categories->links() }}
</div>
@endsection
