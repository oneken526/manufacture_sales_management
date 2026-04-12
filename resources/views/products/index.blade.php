@extends('layouts.app')

@section('title', '商品管理')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">商品管理</h1>
    <a href="{{ route('products.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
        ＋ 新規登録
    </a>
</div>

{{-- 検索フォーム --}}
<form method="GET" action="{{ route('products.index') }}" class="mb-4 flex flex-wrap gap-2">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="商品名・コードで検索"
           class="border border-gray-300 rounded px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-400">
    <select name="category_id"
            class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        <option value="">全カテゴリ</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    <button type="submit"
            class="px-4 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
        検索
    </button>
    @if(request('search') || request('category_id'))
        <a href="{{ route('products.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
            クリア
        </a>
    @endif
</form>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">商品コード</th>
                <th class="px-4 py-3 text-left">商品名</th>
                <th class="px-4 py-3 text-left">カテゴリ</th>
                <th class="px-4 py-3 text-right">標準単価</th>
                <th class="px-4 py-3 text-center">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-gray-700">{{ $product->code }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">
                    <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:underline">
                        {{ $product->name }}
                    </a>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $product->category?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-right text-gray-700">¥{{ number_format($product->unit_price) }}</td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('products.edit', $product) }}"
                       class="text-indigo-600 hover:underline text-xs mr-3">編集</a>
                    <form method="POST" action="{{ route('products.destroy', $product) }}"
                          class="inline"
                          onsubmit="return confirm('「{{ $product->name }}」を削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                    商品が登録されていません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection
