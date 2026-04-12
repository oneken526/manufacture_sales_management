<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '製造業販売管理システム')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans">

    {{-- ヘッダー --}}
    <header class="bg-slate-800 h-16 flex items-center px-6 justify-between fixed top-0 left-0 right-0 z-10 shadow-md">
        <div class="flex items-center gap-3">
            {{-- ロゴアイコン --}}
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="text-white font-bold text-base tracking-wide">製造業販売管理システム</span>
        </div>

        <div class="flex items-center gap-4">
            {{-- ユーザーアイコン + 名前 --}}
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-slate-600 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="text-slate-300 text-sm">{{ auth()->user()->name }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-xs text-slate-400 hover:text-white border border-slate-600 hover:border-slate-400 rounded px-3 py-1 transition-colors">
                    ログアウト
                </button>
            </form>
        </div>
    </header>

    <div class="flex pt-16">
        {{-- サイドバー --}}
        <nav class="w-64 bg-slate-900 min-h-screen pt-4 fixed left-0 top-16 bottom-0 overflow-y-auto">
            @include('layouts.partials.sidebar')
        </nav>

        {{-- メインコンテンツ --}}
        <main class="flex-1 p-8 ml-64 min-h-screen">
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-lg px-4 py-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg px-4 py-3 shadow-sm">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
