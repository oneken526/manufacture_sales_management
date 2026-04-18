@extends('layouts.app')

@section('title', 'ダッシュボード（倉庫）')

@section('content')
<div class="dashboard-header">
    <h1 class="page-title">ダッシュボード</h1>
    <p class="page-subtitle">製造業販売管理システム — 倉庫ビュー</p>
</div>

<div class="dashboard-grid--2">

    <div class="dashboard-card dashboard-card--cyan">
        <div class="dashboard-card-top">
            <div class="dashboard-card-icon dashboard-card-icon--cyan">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
            <span class="dashboard-card-badge dashboard-card-badge--cyan">出荷</span>
        </div>
        <h3 class="dashboard-card-title">出荷管理</h3>
        <p class="dashboard-card-desc">出荷指示の確認・出荷実績の登録</p>
        <a href="{{ Route::has('shipments.index') ? route('shipments.index') : '#' }}"
           class="dashboard-card-link dashboard-card-link--cyan">
            開く
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="dashboard-card dashboard-card--emerald">
        <div class="dashboard-card-top">
            <div class="dashboard-card-icon dashboard-card-icon--emerald">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
            </div>
            <span class="dashboard-card-badge dashboard-card-badge--emerald">在庫</span>
        </div>
        <h3 class="dashboard-card-title">在庫管理</h3>
        <p class="dashboard-card-desc">現在庫・引当在庫の確認・棚卸</p>
        <a href="{{ Route::has('stocks.index') ? route('stocks.index') : '#' }}"
           class="dashboard-card-link dashboard-card-link--emerald">
            開く
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</div>
@endsection
