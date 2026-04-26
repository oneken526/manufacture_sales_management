@extends('layouts.app')

@section('title', '商品詳細')

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
.btn-set { display: flex; gap: 0.5rem; }
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
}
.submit-btn:hover { opacity: .85; }
.submit-btn--primary { background: #4f46e5; color: #fff; }
.submit-btn--ghost   { background: #e2e8f0; color: #475569; }
.submit-btn--danger  { background: #ef4444; color: #fff; }
.cat-label {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    background: #f1f5f9;
    color: #475569;
}
.prd-detail {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.prd-detail-head {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
}
.prd-detail-icon {
    width: 40px;
    height: 40px;
    background: #ede9fe;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7c3aed;
    flex-shrink: 0;
}
.prd-detail-icon svg { width: 20px; height: 20px; }
.prd-detail-name { font-size: 1rem; font-weight: 600; color: #1e293b; margin: 0; }
.prd-detail-code { font-size: 0.8rem; color: #94a3b8; margin: 0.125rem 0 0; font-family: monospace; }
.prd-dl { margin: 0; }
.prd-row {
    display: flex;
    padding: 0.75rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.875rem;
}
.prd-row:last-child { border-bottom: none; }
.prd-row--muted { background: #fafafa; }
.prd-dt { width: 180px; flex-shrink: 0; color: #64748b; font-weight: 500; }
.prd-dd { color: #1e293b; margin: 0; }
.prd-dd--mono { font-family: monospace; font-size: 0.85rem; }
.prd-dd--xs { font-size: 0.8rem; color: #94a3b8; }
.danger-block {
    background: #fff;
    border: 1px solid #fecaca;
    border-radius: 0.5rem;
    padding: 1.25rem 1.5rem;
}
.danger-block-title { font-weight: 700; color: #991b1b; margin: 0 0 0.25rem; font-size: 0.9rem; }
.danger-block-desc  { font-size: 0.875rem; color: #64748b; margin: 0 0 1rem; }
</style>

<div class="prd-header">
    <div>
        <h1 class="prd-title">商品詳細</h1>
        <p class="prd-subtitle">{{ $product->code }}</p>
    </div>
    <div class="btn-set">
        <a href="{{ route('products.edit', $product) }}" class="submit-btn submit-btn--primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            編集
        </a>
        <a href="{{ route('products.index') }}" class="submit-btn submit-btn--ghost">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            一覧へ戻る
        </a>
    </div>
</div>

<div class="prd-detail">
    <div class="prd-detail-head">
        <div class="prd-detail-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div>
            <p class="prd-detail-name">{{ $product->name }}</p>
            <p class="prd-detail-code">{{ $product->code }}</p>
        </div>
    </div>

    <dl class="prd-dl">
        <div class="prd-row">
            <dt class="prd-dt">商品コード</dt>
            <dd class="prd-dd prd-dd--mono">{{ $product->code }}</dd>
        </div>
        <div class="prd-row">
            <dt class="prd-dt">商品名</dt>
            <dd class="prd-dd">{{ $product->name }}</dd>
        </div>
        <div class="prd-row">
            <dt class="prd-dt">カテゴリ</dt>
            <dd class="prd-dd">
                @if($product->category)
                    <span class="cat-label">{{ $product->category->name }}</span>
                @else
                    <span style="color:#94a3b8;">—</span>
                @endif
            </dd>
        </div>
        <div class="prd-row">
            <dt class="prd-dt">標準単価</dt>
            <dd class="prd-dd">¥{{ number_format($product->unit_price) }}</dd>
        </div>
        <div class="prd-row prd-row--muted">
            <dt class="prd-dt">登録日時</dt>
            <dd class="prd-dd prd-dd--xs">{{ $product->created_at->format('Y/m/d H:i') }}</dd>
        </div>
        <div class="prd-row prd-row--muted">
            <dt class="prd-dt">更新日時</dt>
            <dd class="prd-dd prd-dd--xs">{{ $product->updated_at->format('Y/m/d H:i') }}</dd>
        </div>
    </dl>
</div>

<div class="danger-block">
    <p class="danger-block-title">危険な操作</p>
    <p class="danger-block-desc">この商品を削除すると元に戻せません。</p>
    <form method="POST" action="{{ route('products.destroy', $product) }}"
          onsubmit="return confirm('「{{ $product->name }}」を削除しますか？')">
        @csrf
        @method('DELETE')
        <button type="submit" class="submit-btn submit-btn--danger">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            この商品を削除する
        </button>
    </form>
</div>
@endsection
