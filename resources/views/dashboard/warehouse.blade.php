@extends('layouts.app')

@section('title', 'ダッシュボード（倉庫）')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">ダッシュボード</h1>
    <p class="text-sm text-slate-500 mt-1">製造業販売管理システム — 倉庫ビュー</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <x-ui.dashboard-card color="cyan" badge="出荷" title="出荷管理" description="出荷指示の確認・出荷実績の登録"
        :href="Route::has('shipments.index') ? route('shipments.index') : '#'">
        <x-slot:icon>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
        </x-slot:icon>
    </x-ui.dashboard-card>

    <x-ui.dashboard-card color="emerald" badge="在庫" title="在庫管理" description="現在庫・引当在庫の確認・棚卸"
        :href="Route::has('stocks.index') ? route('stocks.index') : '#'">
        <x-slot:icon>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
            </svg>
        </x-slot:icon>
    </x-ui.dashboard-card>

</div>
@endsection
