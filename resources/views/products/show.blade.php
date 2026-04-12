@extends('layouts.app')

@section('title', '商品詳細')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">商品詳細</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $product->code }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('products.edit', $product) }}"
           class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">編集</a>
        <a href="{{ route('products.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">一覧へ戻る</a>
    </div>
</div>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <dl class="grid grid-cols-1 divide-y divide-gray-100">

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">商品コード</dt>
            <dd class="col-span-2 text-sm text-gray-800 font-mono">{{ $product->code }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">商品名</dt>
            <dd class="col-span-2 text-sm text-gray-800 font-medium">{{ $product->name }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">カテゴリ</dt>
            <dd class="col-span-2 text-sm text-gray-800">{{ $product->category?->name ?? '—' }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">標準単価</dt>
            <dd class="col-span-2 text-sm text-gray-800">¥{{ number_format($product->unit_price) }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">登録日時</dt>
            <dd class="col-span-2 text-sm text-gray-600">{{ $product->created_at->format('Y/m/d H:i') }}</dd>
        </div>

        <div class="py-3 grid grid-cols-3">
            <dt class="text-sm font-medium text-gray-500">更新日時</dt>
            <dd class="col-span-2 text-sm text-gray-600">{{ $product->updated_at->format('Y/m/d H:i') }}</dd>
        </div>

    </dl>
</div>

<div class="mt-4 max-w-2xl">
    <form method="POST" action="{{ route('products.destroy', $product) }}"
          onsubmit="return confirm('「{{ $product->name }}」を削除しますか？')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="px-4 py-2 bg-red-500 text-white text-sm rounded hover:bg-red-600">
            この商品を削除する
        </button>
    </form>
</div>
@endsection
