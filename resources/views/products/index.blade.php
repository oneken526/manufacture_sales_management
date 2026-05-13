@extends('layouts.app')

@section('title', '商品管理')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">商品管理</h1>
        <p class="text-sm text-slate-500 mt-0.5">商品の登録・編集・管理</p>
    </div>
    <x-buttons.link-button :href="route('products.create')">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規登録
    </x-buttons.link-button>
</div>

{{-- 検索フォーム --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-5">
    <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-2 items-center">
        <x-inputs.text-input variant="search" type="text" name="search" value="{{ request('search') }}"
               placeholder="商品名・コードで検索" class="w-64" />
        <x-inputs.select name="category_id">
            <option value="">全カテゴリ</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </x-inputs.select>
        <x-buttons.search-button>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            検索
        </x-buttons.search-button>
        @if(request('search') || request('category_id'))
            <x-buttons.link-button variant="ghost" :href="route('products.index')">クリア</x-buttons.link-button>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-800 text-slate-200">
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">商品コード</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">商品名</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">カテゴリ</th>
                <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wider">標準単価</th>
                <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($products as $product)
            <tr class="hover:bg-indigo-50 transition-colors">
                <td class="px-5 py-3.5 font-mono text-slate-500 text-xs">{{ $product->code }}</td>
                <td class="px-5 py-3.5 font-medium text-slate-800">
                    <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">
                        {{ $product->name }}
                    </a>
                </td>
                <td class="px-5 py-3.5">
                    @if($product->category)
                        <x-badges.badge variant="default">{{ $product->category->name }}</x-badges.badge>
                    @else
                        <span class="text-slate-400">—</span>
                    @endif
                </td>
                <td class="px-5 py-3.5 text-right font-medium text-slate-700">¥{{ number_format($product->unit_price) }}</td>
                <td class="px-5 py-3.5 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <x-buttons.table-action variant="edit" :href="route('products.edit', $product)">編集</x-buttons.table-action>
                        <form method="POST" action="{{ route('products.destroy', $product) }}"
                              class="inline"
                              onsubmit="return confirm('「{{ $product->name }}」を削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <x-buttons.table-action variant="delete">削除</x-buttons.table-action>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
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
