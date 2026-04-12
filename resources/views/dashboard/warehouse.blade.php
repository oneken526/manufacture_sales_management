@extends('layouts.app')

@section('title', 'ダッシュボード（倉庫）')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">ダッシュボード</h1>
    <p class="text-sm text-slate-500 mt-1">製造業販売管理システム — 倉庫ビュー</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-cyan-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded-full">出荷</span>
        </div>
        <h3 class="text-base font-semibold text-slate-700 mb-1">出荷管理</h3>
        <p class="text-sm text-slate-400 mb-4">出荷指示の確認・出荷実績の登録</p>
        <a href="{{ Route::has('shipments.index') ? route('shipments.index') : '#' }}"
           class="text-xs font-medium text-cyan-600 hover:text-cyan-800 inline-flex items-center gap-1">
            開く
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">在庫</span>
        </div>
        <h3 class="text-base font-semibold text-slate-700 mb-1">在庫管理</h3>
        <p class="text-sm text-slate-400 mb-4">現在庫・引当在庫の確認・棚卸</p>
        <a href="{{ Route::has('stocks.index') ? route('stocks.index') : '#' }}"
           class="text-xs font-medium text-emerald-600 hover:text-emerald-800 inline-flex items-center gap-1">
            開く
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</div>
@endsection
