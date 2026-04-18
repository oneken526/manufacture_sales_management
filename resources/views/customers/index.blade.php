@extends('layouts.app')

@section('title', '得意先管理')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">得意先管理</h1>
        <p class="page-subtitle">得意先の登録・編集・管理</p>
    </div>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規登録
    </a>
</div>

{{-- 検索フォーム --}}
<div class="search-box">
    <form method="GET" action="{{ route('customers.index') }}" class="search-form">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="得意先名・コードで検索"
               class="search-input">
        <button type="submit" class="btn btn-dark">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            検索
        </button>
        @if(request('search'))
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">クリア</a>
        @endif
    </form>
</div>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>得意先コード</th>
                <th>得意先名</th>
                <th>メールアドレス</th>
                <th>締日</th>
                <th class="col-right">与信限度額</th>
                <th class="col-center">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            <tr>
                <td class="td-code">{{ $customer->code }}</td>
                <td class="td-name">
                    <a href="{{ route('customers.show', $customer) }}" class="td-link">
                        {{ $customer->name }}
                    </a>
                </td>
                <td>{{ $customer->email ?? '—' }}</td>
                <td>{{ $customer->closing_day_label }}</td>
                <td class="col-right">
                    @if($customer->credit_limit > 0)
                        ¥{{ number_format($customer->credit_limit) }}
                    @else
                        <span class="badge badge-none">制限なし</span>
                    @endif
                </td>
                <td class="col-center">
                    <div class="td-actions">
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-edit btn-sm">編集</a>
                        <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                              onsubmit="return confirm('「{{ $customer->name }}」を削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete btn-sm">削除</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="table-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    得意先が登録されていません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-pagination">
    {{ $customers->links() }}
</div>
@endsection
