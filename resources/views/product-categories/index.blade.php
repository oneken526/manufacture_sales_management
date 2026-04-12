@extends('layouts.app')

@section('title', '商品カテゴリ管理')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">商品カテゴリ管理</h1>
    <a href="{{ route('product-categories.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
        ＋ 新規登録
    </a>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">カテゴリ名</th>
                <th class="px-4 py-3 text-right">商品数</th>
                <th class="px-4 py-3 text-center">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $category->name }}</td>
                <td class="px-4 py-3 text-right text-gray-600">{{ number_format($category->products_count) }}</td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('product-categories.edit', $category) }}"
                       class="text-indigo-600 hover:underline text-xs mr-3">編集</a>
                    <form method="POST" action="{{ route('product-categories.destroy', $category) }}"
                          class="inline"
                          onsubmit="return confirm('「{{ $category->name }}」を削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                    カテゴリが登録されていません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>
@endsection
