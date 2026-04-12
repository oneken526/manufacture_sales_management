@extends('layouts.app')

@section('title', 'カテゴリ編集')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">商品カテゴリ編集</h1>
    <p class="text-sm text-slate-500 mt-0.5">{{ $productCategory->name }}</p>
</div>

<div class="bg-white rounded-xl shadow-md p-6 max-w-md">
    <form method="POST" action="{{ route('product-categories.update', $productCategory) }}">
        @csrf
        @method('PUT')
        @include('product-categories._form')
        <div class="flex gap-3 mt-6 pt-5 border-t border-slate-100">
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow-sm transition-colors">
                更新する
            </button>
            <a href="{{ route('product-categories.index') }}"
               class="px-5 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                キャンセル
            </a>
        </div>
    </form>
</div>
@endsection
