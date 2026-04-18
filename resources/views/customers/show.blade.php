@extends('layouts.app')

@section('title', '得意先詳細')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">得意先詳細</h1>
        <p class="page-subtitle">{{ $customer->code }}</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            編集
        </a>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            一覧へ戻る
        </a>
    </div>
</div>

<div class="detail-card">
    <div class="detail-card-header">
        <div class="detail-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="detail-name">{{ $customer->name }}</p>
            <p class="detail-code">{{ $customer->code }}</p>
        </div>
    </div>

    <dl class="detail-dl">
        <div class="detail-row">
            <dt class="detail-dt">得意先コード</dt>
            <dd class="detail-dd detail-dd--mono">{{ $customer->code }}</dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">得意先名</dt>
            <dd class="detail-dd">{{ $customer->name }}</dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">フリガナ</dt>
            <dd class="detail-dd detail-dd--muted">{{ $customer->name_kana ?? '—' }}</dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">郵便番号</dt>
            <dd class="detail-dd detail-dd--muted">{{ $customer->postal_code ?? '—' }}</dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">住所</dt>
            <dd class="detail-dd detail-dd--muted">{{ $customer->address ?? '—' }}</dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">電話番号</dt>
            <dd class="detail-dd detail-dd--muted">{{ $customer->phone ?? '—' }}</dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">メールアドレス</dt>
            <dd class="detail-dd detail-dd--muted">{{ $customer->email ?? '—' }}</dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">締日</dt>
            <dd class="detail-dd detail-dd--muted">{{ $customer->closing_day_label }}</dd>
        </div>
        <div class="detail-row">
            <dt class="detail-dt">与信限度額</dt>
            <dd class="detail-dd detail-dd--muted">
                @if($customer->credit_limit > 0)
                    ¥{{ number_format($customer->credit_limit) }}
                @else
                    <span class="badge badge-none">制限なし</span>
                @endif
            </dd>
        </div>
        <div class="detail-row detail-row--start">
            <dt class="detail-dt">備考</dt>
            <dd class="detail-dd detail-dd--muted detail-dd--pre">{{ $customer->notes ?? '—' }}</dd>
        </div>
        <div class="detail-row detail-row--muted">
            <dt class="detail-dt">登録日時</dt>
            <dd class="detail-dd detail-dd--xs">{{ $customer->created_at->format('Y/m/d H:i') }}</dd>
        </div>
        <div class="detail-row detail-row--muted">
            <dt class="detail-dt">更新日時</dt>
            <dd class="detail-dd detail-dd--xs">{{ $customer->updated_at->format('Y/m/d H:i') }}</dd>
        </div>
    </dl>
</div>

{{-- 削除 --}}
<div class="danger-zone">
    <p class="danger-zone-title">危険な操作</p>
    <p class="danger-zone-desc">この得意先を削除すると元に戻せません。</p>
    <form method="POST" action="{{ route('customers.destroy', $customer) }}"
          onsubmit="return confirm('「{{ $customer->name }}」を削除しますか？')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            この得意先を削除する
        </button>
    </form>
</div>
@endsection
