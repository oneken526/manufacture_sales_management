@extends('layouts.app')

@section('title', '商品登録')

@section('content')
<div class="page-header-simple">
    <h1 class="page-title">商品登録</h1>
    <p class="page-subtitle">新しい商品を登録します</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        @include('products._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">登録する</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
