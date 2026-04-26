@extends('layouts.app')

@section('title', '商品管理')

@section('content')
<style>
.prd-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}
.prd-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.prd-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0.25rem 0 0;
}
.prd-search {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}
.prd-search__input,
.prd-search__select {
    border: 1px solid #cbd5e1;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    outline: none;
    background: #fff;
}
.prd-search__input { flex: 1; min-width: 160px; }
.prd-search__select { min-width: 140px; }
.prd-search__input:focus,
.prd-search__select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,.15);
}
.prd-list {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.prd-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}
.prd-table thead { background: #f8fafc; }
.prd-table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 600;
    color: #475569;
    border-bottom: 1px solid #e2e8f0;
}
.prd-table th.th-right { text-align: right; }
.prd-table th.th-center { text-align: center; }
.prd-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
}
.prd-table td.td-right { text-align: right; font-variant-numeric: tabular-nums; }
.prd-table td.td-center { text-align: center; }
.prd-table tbody tr:last-child td { border-bottom: none; }
.prd-table tbody tr:hover { background: #f8fafc; }
.prd-code { font-family: monospace; font-size: 0.8rem; color: #64748b; }
.prd-link { color: #4f46e5; text-decoration: none; }
.prd-link:hover { text-decoration: underline; }
.prd-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #94a3b8;
    font-size: 0.875rem;
}
.cat-label {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    background: #f1f5f9;
    color: #475569;
}
.row-ops { display: flex; gap: 0.5rem; justify-content: center; }
.submit-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    border: none;
    text-decoration: none;
    transition: opacity .15s;
    line-height: 1;
}
.submit-btn:hover { opacity: .85; }
.submit-btn--primary { background: #4f46e5; color: #fff; }
.submit-btn--dark    { background: #334155; color: #fff; }
.submit-btn--ghost   { background: #e2e8f0; color: #475569; }
.submit-btn--edit    { background: #eff6ff; color: #1d4ed8; }
.submit-btn--danger  { background: #fee2e2; color: #b91c1c; }
.submit-btn--sm      { font-size: 0.8rem; padding: 0.375rem 0.75rem; }
</style>

<div class="prd-header">
    <div>
        <h1 class="prd-title">商品管理</h1>
        <p class="prd-subtitle">商品の登録・編集・管理</p>
    </div>
    <a href="{{ route('products.create') }}" class="submit-btn submit-btn--primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規登録
    </a>
</div>

<div class="prd-search">
    <form method="GET" action="{{ route('products.index') }}" style="display:contents;">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="商品名・コードで検索"
               class="prd-search__input">
        <select name="category_id" class="prd-search__select">
            <option value="">全カテゴリ</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="submit-btn submit-btn--dark">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            検索
        </button>
        @if(request('search') || request('category_id'))
            <a href="{{ route('products.index') }}" class="submit-btn submit-btn--ghost">クリア</a>
        @endif
    </form>
</div>

<div class="prd-list">
    <table class="prd-table">
        <thead>
            <tr>
                <th>商品コード</th>
                <th>商品名</th>
                <th>カテゴリ</th>
                <th class="th-right">標準単価</th>
                <th class="th-center">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td class="prd-code">{{ $product->code }}</td>
                <td>
                    <a href="{{ route('products.show', $product) }}" class="prd-link">
                        {{ $product->name }}
                    </a>
                </td>
                <td>
                    @if($product->category)
                        <span class="cat-label">{{ $product->category->name }}</span>
                    @else
                        <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td class="td-right">¥{{ number_format($product->unit_price) }}</td>
                <td class="td-center">
                    <div class="row-ops">
                        <a href="{{ route('products.edit', $product) }}" class="submit-btn submit-btn--edit submit-btn--sm">編集</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}"
                              onsubmit="return confirm('「{{ $product->name }}」を削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="submit-btn submit-btn--danger submit-btn--sm">削除</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="prd-empty">
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
