@extends('layouts.app')

@section('title', 'ダッシュボード（管理者）')

@section('content')
<div class="dashboard-header">
    <h1 class="page-title">ダッシュボード</h1>
    <p class="page-subtitle">製造業販売管理システム — 管理者ビュー</p>
</div>

<div class="dashboard-grid">

    <div class="dashboard-card dashboard-card--indigo">
        <div class="dashboard-card-top">
            <div class="dashboard-card-icon dashboard-card-icon--indigo">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <span class="dashboard-card-badge dashboard-card-badge--indigo">販売</span>
        </div>
        <h3 class="dashboard-card-title">受注管理</h3>
        <p class="dashboard-card-desc">受注の確認・登録・進捗管理</p>
        <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}"
           class="dashboard-card-link dashboard-card-link--indigo">
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
        <p class="dashboard-card-desc">現在庫・引当在庫の確認</p>
        <a href="{{ Route::has('stocks.index') ? route('stocks.index') : '#' }}"
           class="dashboard-card-link dashboard-card-link--emerald">
            開く
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="dashboard-card dashboard-card--amber">
        <div class="dashboard-card-top">
            <div class="dashboard-card-icon dashboard-card-icon--amber">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
            </div>
            <span class="dashboard-card-badge dashboard-card-badge--amber">請求</span>
        </div>
        <h3 class="dashboard-card-title">請求管理</h3>
        <p class="dashboard-card-desc">請求書の発行・入金消込</p>
        <a href="{{ Route::has('invoices.index') ? route('invoices.index') : '#' }}"
           class="dashboard-card-link dashboard-card-link--amber">
            開く
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</div>

{{-- マスタ管理へのクイックリンク --}}
<div class="quick-links">
    <h2 class="quick-links-title">マスタ管理</h2>
    <div class="quick-links-grid">
        <a href="{{ Route::has('customers.index') ? route('customers.index') : '#' }}" class="quick-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            得意先管理
        </a>
        <a href="{{ Route::has('products.index') ? route('products.index') : '#' }}" class="quick-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            商品管理
        </a>
        <a href="{{ Route::has('warehouses.index') ? route('warehouses.index') : '#' }}" class="quick-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
            </svg>
            倉庫管理
        </a>
        <a href="{{ Route::has('users.index') ? route('users.index') : '#' }}" class="quick-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            ユーザー管理
        </a>
    </div>
</div>
@endsection
