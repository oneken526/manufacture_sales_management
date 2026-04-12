@extends('layouts.app')

@section('title', 'カテゴリ登録')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">商品カテゴリ登録</h1>
</div>

<div class="bg-white rounded shadow p-6 max-w-md">
    <form method="POST" action="{{ route('product-categories.store') }}">
        @csrf
        @include('product-categories._form')
        <div class="flex gap-3 mt-6">
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                登録する
            </button>
            <a href="{{ route('product-categories.index') }}"
               class="px-6 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                キャンセル
            </a>
        </div>
    </form>
</div>
@endsection
