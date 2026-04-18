@extends('layouts.app')

@section('title', '得意先登録')

@section('content')
<div class="page-header-simple">
    <h1 class="page-title">得意先登録</h1>
    <p class="page-subtitle">新しい得意先を登録します</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('customers.store') }}">
        @csrf
        @include('customers._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">登録する</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
