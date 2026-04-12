@extends('layouts.app')

@section('title', 'ダッシュボード（営業）')

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">ダッシュボード</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">見積</h3>
            <p class="text-sm text-gray-500">見積管理へ移動して確認してください。</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">受注</h3>
            <p class="text-sm text-gray-500">受注管理へ移動して確認してください。</p>
        </div>
    </div>
</div>
@endsection
