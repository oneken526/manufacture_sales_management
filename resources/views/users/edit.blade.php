@extends('layouts.app')

@section('title', 'ユーザー編集')

@section('content')
<style>
.user-page-header-simple {
    margin-bottom: 1.5rem;
}
.user-page-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.user-page-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0.25rem 0 0;
}
.user-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1.5rem;
}
.action-btn {
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
.action-btn:hover { opacity: .85; }
.action-btn--primary   { background: #4f46e5; color: #fff; }
.action-btn--secondary { background: #e2e8f0; color: #475569; }
</style>

<div class="user-page-header-simple">
    <h1 class="user-page-title">ユーザー編集</h1>
    <p class="user-page-subtitle">{{ $user->name }}</p>
</div>

<div class="user-card">
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        @include('users._form')
        <div class="user-form-actions">
            <button type="submit" class="action-btn action-btn--primary">更新する</button>
            <a href="{{ route('users.index') }}" class="action-btn action-btn--secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
