<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '製造業販売管理システム')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <h1 class="text-center text-2xl font-bold text-gray-800 mb-8">
            製造業販売管理システム
        </h1>
        <div class="bg-white shadow-md rounded-lg px-8 py-6">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
