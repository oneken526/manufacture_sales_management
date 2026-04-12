@extends('layouts.app')

@section('title', 'ダッシュボード（管理者）')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">ダッシュボード</h1>
    <p class="text-sm text-slate-500 mt-1">製造業販売管理システム — 管理者ビュー</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">販売</span>
        </div>
        <h3 class="text-base font-semibold text-slate-700 mb-1">受注管理</h3>
        <p class="text-sm text-slate-400 mb-4">受注の確認・登録・進捗管理</p>
        <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}"
           class="text-xs font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
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
        <p class="text-sm text-slate-400 mb-4">現在庫・引当在庫の確認</p>
        <a href="{{ Route::has('stocks.index') ? route('stocks.index') : '#' }}"
           class="text-xs font-medium text-emerald-600 hover:text-emerald-800 inline-flex items-center gap-1">
            開く
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">請求</span>
        </div>
        <h3 class="text-base font-semibold text-slate-700 mb-1">請求管理</h3>
        <p class="text-sm text-slate-400 mb-4">請求書の発行・入金消込</p>
        <a href="{{ Route::has('invoices.index') ? route('invoices.index') : '#' }}"
           class="text-xs font-medium text-amber-600 hover:text-amber-800 inline-flex items-center gap-1">
            開く
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</div>

{{-- マスタ管理へのクイックリンク --}}
<div class="mt-6 bg-white rounded-xl shadow-md p-6">
    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">マスタ管理</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ Route::has('customers.index') ? route('customers.index') : '#' }}"
           class="flex items-center gap-2 px-4 py-3 bg-slate-50 hover:bg-indigo-50 rounded-lg text-sm text-slate-600 hover:text-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            得意先管理
        </a>
        <a href="{{ Route::has('products.index') ? route('products.index') : '#' }}"
           class="flex items-center gap-2 px-4 py-3 bg-slate-50 hover:bg-indigo-50 rounded-lg text-sm text-slate-600 hover:text-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            商品管理
        </a>
        <a href="{{ Route::has('warehouses.index') ? route('warehouses.index') : '#' }}"
           class="flex items-center gap-2 px-4 py-3 bg-slate-50 hover:bg-indigo-50 rounded-lg text-sm text-slate-600 hover:text-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
            </svg>
            倉庫管理
        </a>
        <a href="{{ Route::has('users.index') ? route('users.index') : '#' }}"
           class="flex items-center gap-2 px-4 py-3 bg-slate-50 hover:bg-indigo-50 rounded-lg text-sm text-slate-600 hover:text-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ユーザー管理
        </a>
    </div>
</div>
@endsection
