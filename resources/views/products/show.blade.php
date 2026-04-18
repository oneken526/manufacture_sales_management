@extends('layouts.app')

@section('title', '商品詳細')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">商品詳細</h1>
        <p class="page-subtitle">{{ $product->code }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            編集
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            一覧へ戻る
        </a>
    </div>
</div>

<div class="detail-card">
    <div class="detail-card-header">
        <div class="detail-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div>
            <p class="detail-name">{{ $product->name }}</p>
            <p class="detail-code">{{ $product->code }}</p>
        </div>
    </div>

    <dl class="detail-dl">
        <div class="detail-row">
            <dt class="detail-dt">商品コード</dt>
            <dd class="detail-dd detail-dd--mono">{{ $product->code }}</dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">商品名</dt>
            <dd class="detail-dd">{{ $product->name }}</dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">カテゴリ</dt>
            <dd class="detail-dd">
                @if($product->category)
                    <span class="badge badge-secondary">{{ $product->category->name }}</span>
                @else
                    <span class="detail-dd--muted">—</span>
                @endif
            </dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">標準単価</dt>
            <dd class="detail-dd">¥{{ number_format($product->unit_price) }}</dd>
        </div>
        <div class="detail-row detail-row--muted">
            <dt class="detail-dt">登録日時</dt>
            <dd class="detail-dd detail-dd--xs">{{ $product->created_at->format('Y/m/d H:i') }}</dd>
        </div>
        <div class="detail-row detail-row--muted">
            <dt class="detail-dt">更新日時</dt>
            <dd class="detail-dd detail-dd--xs">{{ $product->updated_at->format('Y/m/d H:i') }}</dd>
        </div>
    </dl>
</div>

{{-- 削除 --}}
<div class="danger-zone">
    <p class="danger-zone-title">危険な操作</p>
    <p class="danger-zone-desc">この商品を削除すると元に戻せません。</p>
    <form method="POST" action="{{ route('products.destroy', $product) }}"
          onsubmit="return confirm('「{{ $product->name }}」を削除しますか？')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            この商品を削除する
        </button>
    </form>
</div>
@endsection
