@extends('layouts.app')

@section('title', '特別単価管理 - ' . $customer->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">特別単価管理</h1>
        <p class="text-sm text-slate-500 mt-0.5">{{ $customer->name }}（{{ $customer->code }}）</p>
    </div>
    <a href="{{ route('customers.show', $customer) }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        得意先詳細へ戻る
    </a>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">
    {{ session('success') }}
</div>
@endif

{{-- 特別単価一覧 --}}
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="text-base font-semibold text-slate-800">登録済み特別単価</h2>
    </div>
    @if($specialPrices->isEmpty())
    <div class="px-6 py-10 text-center text-sm text-slate-400">
        特別単価は登録されていません。
    </div>
    @else
    <table class="min-w-full divide-y divide-slate-100">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">品番</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">商品名</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">標準単価</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">特別単価</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($specialPrices as $sp)
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-3 text-sm font-mono text-slate-600">{{ $sp->product->code }}</td>
                <td class="px-6 py-3 text-sm text-slate-800">{{ $sp->product->name }}</td>
                <td class="px-6 py-3 text-sm text-right text-slate-500">¥{{ number_format($sp->product->unit_price) }}</td>
                <td class="px-6 py-3 text-sm text-right font-medium text-indigo-700">¥{{ number_format($sp->unit_price) }}</td>
                <td class="px-6 py-3 text-center">
                    <div class="flex items-center justify-center gap-2">
                        {{-- 編集フォーム --}}
                        <form method="POST"
                              action="{{ route('customers.special-prices.update', [$customer, $sp]) }}"
                              class="inline-flex items-center gap-1">
                            @csrf
                            @method('PUT')
                            <input type="number" name="unit_price"
                                   value="{{ $sp->unit_price }}"
                                   min="0" step="1"
                                   class="w-24 px-2 py-1 text-sm border border-slate-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors">
                                更新
                            </button>
                        </form>
                        {{-- 削除フォーム --}}
                        <form method="POST"
                              action="{{ route('customers.special-prices.destroy', [$customer, $sp]) }}"
                              onsubmit="return confirm('「{{ $sp->product->name }}」の特別単価を削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors">
                                削除
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

{{-- 新規登録フォーム --}}
@if($products->isNotEmpty())
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="text-base font-semibold text-slate-800">特別単価を追加</h2>
    </div>
    <div class="px-6 py-5">
        <form method="POST" action="{{ route('customers.special-prices.store', $customer) }}"
              class="flex items-end gap-3">
            @csrf
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">商品</label>
                <select name="product_id"
                        class="w-full px-3 py-2 text-sm border @error('product_id') border-red-400 @else border-slate-300 @enderror rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">商品を選択してください</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                        {{ $product->code }} - {{ $product->name }} (標準: ¥{{ number_format($product->unit_price) }})
                    </option>
                    @endforeach
                </select>
                @error('product_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="w-32">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">特別単価</label>
                <input type="number" name="unit_price" value="{{ old('unit_price') }}"
                       min="0" step="1" placeholder="0"
                       class="w-full px-3 py-2 text-sm border @error('unit_price') border-red-400 @else border-slate-300 @enderror rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                @error('unit_price')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition-colors">
                登録
            </button>
        </form>
    </div>
</div>
@endif
@endsection
