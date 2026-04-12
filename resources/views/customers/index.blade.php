@extends('layouts.app')

@section('title', '得意先管理')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">得意先管理</h1>
    <a href="{{ route('customers.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
        ＋ 新規登録
    </a>
</div>

{{-- 検索フォーム --}}
<form method="GET" action="{{ route('customers.index') }}" class="mb-4 flex gap-2">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="得意先名・コードで検索"
           class="border border-gray-300 rounded px-3 py-2 text-sm w-72 focus:outline-none focus:ring-2 focus:ring-indigo-400">
    <button type="submit"
            class="px-4 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
        検索
    </button>
    @if(request('search'))
        <a href="{{ route('customers.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
            クリア
        </a>
    @endif
</form>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">得意先コード</th>
                <th class="px-4 py-3 text-left">得意先名</th>
                <th class="px-4 py-3 text-left">メールアドレス</th>
                <th class="px-4 py-3 text-left">締日</th>
                <th class="px-4 py-3 text-right">与信限度額</th>
                <th class="px-4 py-3 text-center">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($customers as $customer)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-gray-700">{{ $customer->code }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">
                    <a href="{{ route('customers.show', $customer) }}" class="text-indigo-600 hover:underline">
                        {{ $customer->name }}
                    </a>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $customer->email ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $customer->closing_day_label }}</td>
                <td class="px-4 py-3 text-right text-gray-600">
                    {{ $customer->credit_limit > 0 ? '¥' . number_format($customer->credit_limit) : '制限なし' }}
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('customers.edit', $customer) }}"
                       class="text-indigo-600 hover:underline text-xs mr-3">編集</a>
                    <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                          class="inline"
                          onsubmit="return confirm('「{{ $customer->name }}」を削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                    得意先が登録されていません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $customers->links() }}
</div>
@endsection
