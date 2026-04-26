@extends('layouts.app')

@section('title', '商品登録')

@section('content')
<style>
.prd-page-simple { margin-bottom: 1.5rem; }
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
.form-wrap {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1.5rem;
}
.prd-actions {
    display: flex;
    gap: 0.75rem;
    padding-top: 0.5rem;
    border-top: 1px solid #f1f5f9;
    margin-top: 1.25rem;
}
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
</style>

<div class="prd-page-simple">
    <h1 class="prd-title">商品登録</h1>
    <p class="prd-subtitle">新しい商品を登録します</p>
</div>

<div class="form-wrap">
    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        @include('products._form')
        <div class="prd-actions">
            <button type="submit" class="submit-btn submit-btn--primary">登録する</button>
            <a href="{{ route('products.index') }}" class="submit-btn submit-btn--ghost">キャンセル</a>
        </div>
    </form>
</div>
@endsection
