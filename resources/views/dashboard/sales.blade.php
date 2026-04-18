@extends('layouts.app')

@section('title', 'ダッシュボード（営業）')

@section('content')
<div class="dashboard-header">
    <h1 class="page-title">ダッシュボード</h1>
    <p class="page-subtitle">製造業販売管理システム — 営業ビュー</p>
</div>

<div class="dashboard-grid--2">

    <div class="dashboard-card dashboard-card--indigo">
        <div class="dashboard-card-top">
            <div class="dashboard-card-icon dashboard-card-icon--indigo">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="dashboard-card-badge dashboard-card-badge--indigo">見積</span>
        </div>
        <h3 class="dashboard-card-title">見積管理</h3>
        <p class="dashboard-card-desc">見積書の作成・送付・承認管理</p>
        <a href="{{ Route::has('quotations.index') ? route('quotations.index') : '#' }}"
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
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <span class="dashboard-card-badge dashboard-card-badge--emerald">受注</span>
        </div>
        <h3 class="dashboard-card-title">受注管理</h3>
        <p class="dashboard-card-desc">受注の確認・登録・進捗管理</p>
        <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}"
           class="dashboard-card-link dashboard-card-link--emerald">
            開く
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</div>
@endsection
