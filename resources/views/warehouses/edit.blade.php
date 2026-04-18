@extends('layouts.app')

@section('title', '倉庫編集')

@section('content')
<div class="page-header-simple">
    <h1 class="page-title">倉庫編集</h1>
    <p class="page-subtitle">{{ $warehouse->code }} / {{ $warehouse->name }}</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('warehouses.update', $warehouse) }}">
        @csrf
        @method('PUT')
        @include('warehouses._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">更新する</button>
            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
