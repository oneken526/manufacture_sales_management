@extends('layouts.app')

@section('title', '商品編集')

@section('content')
<div class="page-header-simple">
    <h1 class="page-title">商品編集</h1>
    <p class="page-subtitle">{{ $product->code }} / {{ $product->name }}</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')
        @include('products._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">更新する</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
