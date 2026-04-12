<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '製造業販売管理システム')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    {{-- ヘッダー --}}
    <header class="bg-white shadow-sm h-16 flex items-center px-6 justify-between fixed top-0 left-0 right-0 z-10">
        <div class="text-lg font-bold text-gray-800">製造業販売管理システム</div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:underline">
                    ログアウト
                </button>
            </form>
        </div>
    </header>

    <div class="flex pt-16">
        {{-- サイドバー（ロール別） --}}
        <nav class="w-64 bg-white shadow-md min-h-screen pt-4 fixed left-0 top-16 bottom-0 overflow-y-auto">
            @include('layouts.partials.sidebar')
        </nav>

        {{-- メインコンテンツ --}}
        <main class="flex-1 p-6 ml-64">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
