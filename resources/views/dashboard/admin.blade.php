@extends('layouts.app')

@section('title', 'ダッシュボード（管理者）')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">ダッシュボード</h1>
    <p class="text-sm text-slate-500 mt-1">製造業販売管理システム — 管理者ビュー</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    <x-ui.dashboard-card color="indigo" badge="販売" title="受注管理" description="受注の確認・登録・進捗管理"
        :href="Route::has('orders.index') ? route('orders.index') : '#'">
        <x-slot:icon>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </x-slot:icon>
    </x-ui.dashboard-card>

    <x-ui.dashboard-card color="emerald" badge="在庫" title="在庫管理" description="現在庫・引当在庫の確認"
        :href="Route::has('stocks.index') ? route('stocks.index') : '#'">
        <x-slot:icon>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
            </svg>
        </x-slot:icon>
    </x-ui.dashboard-card>

    <x-ui.dashboard-card color="amber" badge="請求" title="請求管理" description="請求書の発行・入金消込"
        :href="Route::has('invoices.index') ? route('invoices.index') : '#'">
        <x-slot:icon>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
            </svg>
        </x-slot:icon>
    </x-ui.dashboard-card>

</div>

{{-- マスタ管理へのクイックリンク --}}
<div class="mt-6 bg-white rounded-xl shadow-md p-6">
    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">マスタ管理</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        <x-ui.quick-link :href="Route::has('customers.index') ? route('customers.index') : '#'">
            <x-slot:icon>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </x-slot:icon>
            得意先管理
        </x-ui.quick-link>

        <x-ui.quick-link :href="Route::has('products.index') ? route('products.index') : '#'">
            <x-slot:icon>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </x-slot:icon>
            商品管理
        </x-ui.quick-link>

        <x-ui.quick-link :href="Route::has('warehouses.index') ? route('warehouses.index') : '#'">
            <x-slot:icon>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
            </x-slot:icon>
            倉庫管理
        </x-ui.quick-link>

        <x-ui.quick-link :href="Route::has('users.index') ? route('users.index') : '#'">
            <x-slot:icon>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </x-slot:icon>
            ユーザー管理
        </x-ui.quick-link>

    </div>
</div>
@endsection
