@extends('layouts.app')

@section('title', 'ダッシュボード（営業）')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">ダッシュボード</h1>
    <p class="text-sm text-slate-500 mt-1">製造業販売管理システム — 営業ビュー</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <x-ui.dashboard-card color="indigo" badge="見積" title="見積管理" description="見積書の作成・送付・承認管理"
        :href="Route::has('quotations.index') ? route('quotations.index') : '#'">
        <x-slot:icon>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </x-slot:icon>
    </x-ui.dashboard-card>

    <x-ui.dashboard-card color="emerald" badge="受注" title="受注管理" description="受注の確認・登録・進捗管理"
        :href="Route::has('orders.index') ? route('orders.index') : '#'">
        <x-slot:icon>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </x-slot:icon>
    </x-ui.dashboard-card>

</div>
@endsection
