@extends('layouts.app')

@section('title', '得意先詳細')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">得意先詳細</h1>
        <p class="text-sm text-slate-500 mt-0.5">{{ $customer->code }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('customers.edit', $customer) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            編集
        </a>
        <a href="{{ route('customers.index') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            一覧へ戻る
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden max-w-2xl">
    {{-- カードヘッダー --}}
    <div class="bg-slate-800 px-6 py-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-white font-semibold">{{ $customer->name }}</p>
            <p class="text-slate-400 text-xs font-mono">{{ $customer->code }}</p>
        </div>
    </div>

    {{-- 詳細情報 --}}
    <dl class="divide-y divide-slate-100">
        <div class="px-6 py-3.5 grid grid-cols-3 items-center">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">得意先コード</dt>
            <dd class="col-span-2 text-sm text-slate-800 font-mono">{{ $customer->code }}</dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-center">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">得意先名</dt>
            <dd class="col-span-2 text-sm text-slate-800 font-medium">{{ $customer->name }}</dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-center">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">フリガナ</dt>
            <dd class="col-span-2 text-sm text-slate-700">{{ $customer->name_kana ?? '—' }}</dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-center">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">郵便番号</dt>
            <dd class="col-span-2 text-sm text-slate-700">{{ $customer->postal_code ?? '—' }}</dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-center">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">住所</dt>
            <dd class="col-span-2 text-sm text-slate-700">{{ $customer->address ?? '—' }}</dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-center">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">電話番号</dt>
            <dd class="col-span-2 text-sm text-slate-700">{{ $customer->phone ?? '—' }}</dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-center">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">メールアドレス</dt>
            <dd class="col-span-2 text-sm text-slate-700">{{ $customer->email ?? '—' }}</dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-center">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">締日</dt>
            <dd class="col-span-2 text-sm text-slate-700">{{ $customer->closing_day_label }}</dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-center">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">与信限度額</dt>
            <dd class="col-span-2 text-sm text-slate-700">
                @if($customer->credit_limit > 0)
                    <span class="font-medium text-slate-800">¥{{ number_format($customer->credit_limit) }}</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">制限なし</span>
                @endif
            </dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-start">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider pt-0.5">備考</dt>
            <dd class="col-span-2 text-sm text-slate-700 whitespace-pre-wrap">{{ $customer->notes ?? '—' }}</dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-center bg-slate-50">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">登録日時</dt>
            <dd class="col-span-2 text-xs text-slate-500 font-mono">{{ $customer->created_at->format('Y/m/d H:i') }}</dd>
        </div>
        <div class="px-6 py-3.5 grid grid-cols-3 items-center bg-slate-50">
            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">更新日時</dt>
            <dd class="col-span-2 text-xs text-slate-500 font-mono">{{ $customer->updated_at->format('Y/m/d H:i') }}</dd>
        </div>
    </dl>
</div>

{{-- 削除 --}}
<div class="mt-6 max-w-2xl">
    <div class="border border-red-200 rounded-xl p-4 bg-red-50">
        <p class="text-sm text-red-700 font-medium mb-3">危険な操作</p>
        <p class="text-xs text-red-500 mb-3">この得意先を削除すると元に戻せません。</p>
        <form method="POST" action="{{ route('customers.destroy', $customer) }}"
              onsubmit="return confirm('「{{ $customer->name }}」を削除しますか？')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                この得意先を削除する
            </button>
        </form>
    </div>
</div>
@endsection
