@extends('layouts.app')

@section('title', '得意先編集')

@section('content')
<div class="page-header-simple">
    <h1 class="page-title">得意先編集</h1>
    <p class="page-subtitle">{{ $customer->code }} / {{ $customer->name }}</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('customers.update', $customer) }}">
        @csrf
        @method('PUT')
        @include('customers._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">更新する</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
