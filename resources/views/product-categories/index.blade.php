@extends('layouts.app')

@section('title', '商品カテゴリ管理')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">商品カテゴリ管理</h1>
        <p class="text-sm text-slate-500 mt-0.5">商品カテゴリの登録・編集・管理</p>
    </div>
    <a href="{{ route('product-categories.create') }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow-sm transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規登録
    </a>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-800 text-slate-200">
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">カテゴリ名</th>
                <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wider">商品数</th>
                <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($categories as $category)
            <tr class="hover:bg-indigo-50 transition-colors">
                <td class="px-5 py-3.5 font-medium text-slate-800">{{ $category->name }}</td>
                <td class="px-5 py-3.5 text-right">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                        {{ number_format($category->products_count) }} 件
                    </span>
                </td>
                <td class="px-5 py-3.5 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('product-categories.edit', $category) }}"
                           class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors">
                            編集
                        </a>
                        <form method="POST" action="{{ route('product-categories.destroy', $category) }}"
                              class="inline"
                              onsubmit="return confirm('「{{ $category->name }}」を削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                削除
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-5 py-12 text-center text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
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
