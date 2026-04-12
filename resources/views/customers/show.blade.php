@extends('layouts.app')

@section('title', '得意先詳細')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">得意先詳細</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $customer->code }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('customers.edit', $customer) }}"
           class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">編集</a>
        <a href="{{ route('customers.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">一覧へ戻る</a>
    </div>
</div>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <dl class="grid grid-cols-1 divide-y divide-gray-100">

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">得意先コード</dt>
            <dd class="col-span-2 text-sm text-gray-800 font-mono">{{ $customer->code }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">得意先名</dt>
            <dd class="col-span-2 text-sm text-gray-800 font-medium">{{ $customer->name }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">得意先名フリガナ</dt>
            <dd class="col-span-2 text-sm text-gray-800">{{ $customer->name_kana ?? '—' }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">郵便番号</dt>
            <dd class="col-span-2 text-sm text-gray-800">{{ $customer->postal_code ?? '—' }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">住所</dt>
            <dd class="col-span-2 text-sm text-gray-800">{{ $customer->address ?? '—' }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">電話番号</dt>
            <dd class="col-span-2 text-sm text-gray-800">{{ $customer->phone ?? '—' }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">メールアドレス</dt>
            <dd class="col-span-2 text-sm text-gray-800">{{ $customer->email ?? '—' }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">締日</dt>
            <dd class="col-span-2 text-sm text-gray-800">{{ $customer->closing_day_label }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">与信限度額</dt>
            <dd class="col-span-2 text-sm text-gray-800">
                {{ $customer->credit_limit > 0 ? '¥' . number_format($customer->credit_limit) : '制限なし' }}
            </dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">備考</dt>
            <dd class="col-span-2 text-sm text-gray-800 whitespace-pre-wrap">{{ $customer->notes ?? '—' }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">登録日時</dt>
            <dd class="col-span-2 text-sm text-gray-600">{{ $customer->created_at->format('Y/m/d H:i') }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">更新日時</dt>
            <dd class="col-span-2 text-sm text-gray-600">{{ $customer->updated_at->format('Y/m/d H:i') }}</dd>
        </div>

    </dl>
</div>

{{-- 削除フォーム --}}
<div class="mt-4 max-w-2xl">
    <form method="POST" action="{{ route('customers.destroy', $customer) }}"
          onsubmit="return confirm('「{{ $customer->name }}」を削除しますか？')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="px-4 py-2 bg-red-500 text-white text-sm rounded hover:bg-red-600">
            この得意先を削除する
        </button>
    </form>
</div>
@endsection
