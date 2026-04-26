@extends('layouts.app')

@section('title', '倉庫管理')

@section('content')
<style>
.wh-search-input {
    border: 1px solid #cbd5e1;
    border-radius: var(--r);
    padding: 8px 12px;
    font-size: 14px;
    outline: none;
}
</style>
<div class="page-header">
    <div>
        <h1 class="page-title">倉庫管理</h1>
        <p class="page-subtitle">倉庫の登録・編集・管理</p>
    </div>
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規登録
    </a>
</div>

{{-- 検索フォーム --}}
<div class="search-box">
    <form method="GET" action="{{ route('warehouses.index') }}" class="search-form">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="倉庫名・コードで検索"
               class="wh-search-input">
        <button type="submit" class="btn btn-dark">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            検索
        </button>
        @if(request('search'))
            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">クリア</a>
        @endif
    </form>
</div>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>倉庫コード</th>
                <th>倉庫名</th>
                <th class="col-center">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($warehouses as $warehouse)
            <tr>
                <td class="td-code">{{ $warehouse->code }}</td>
                <td class="td-name">{{ $warehouse->name }}</td>
                <td class="col-center">
                    <div class="td-actions">
                        <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-edit btn-sm">編集</a>
                        <form method="POST" action="{{ route('warehouses.destroy', $warehouse) }}"
                              onsubmit="return confirm('「{{ $warehouse->name }}」を削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete btn-sm">削除</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="table-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    倉庫が登録されていません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-pagination">
    {{ $warehouses->links() }}
</div>
@endsection
