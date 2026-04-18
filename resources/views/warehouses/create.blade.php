@extends('layouts.app')

@section('title', '倉庫登録')

@section('content')
<div class="page-header-simple">
    <h1 class="page-title">倉庫登録</h1>
    <p class="page-subtitle">新しい倉庫を登録します</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('warehouses.store') }}">
        @csrf
        @include('warehouses._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">登録する</button>
            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
