@extends('layouts.app')

@section('title', 'カテゴリ編集')

@section('content')
<div class="page-header-simple">
    <h1 class="page-title">商品カテゴリ編集</h1>
    <p class="page-subtitle">{{ $productCategory->name }}</p>
</div>

<div class="card card-sm">
    <form method="POST" action="{{ route('product-categories.update', $productCategory) }}">
        @csrf
        @method('PUT')
        @include('product-categories._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">更新する</button>
            <a href="{{ route('product-categories.index') }}" class="btn btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
