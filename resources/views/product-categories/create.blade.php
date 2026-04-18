@extends('layouts.app')

@section('title', 'カテゴリ登録')

@section('content')
<div class="page-header-simple">
    <h1 class="page-title">商品カテゴリ登録</h1>
    <p class="page-subtitle">新しいカテゴリを登録します</p>
</div>

<div class="card card-sm">
    <form method="POST" action="{{ route('product-categories.store') }}">
        @csrf
        @include('product-categories._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">登録する</button>
            <a href="{{ route('product-categories.index') }}" class="btn btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
