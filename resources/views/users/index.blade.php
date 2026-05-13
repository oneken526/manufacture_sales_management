@extends('layouts.app')

@section('title', 'ユーザー管理')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">ユーザー管理</h1>
        <p class="text-sm text-slate-500 mt-0.5">システムユーザーの登録・編集・管理</p>
    </div>
    <x-buttons.link-button :href="route('users.create')">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規登録
    </x-buttons.link-button>
</div>

{{-- 検索フォーム --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-5">
    <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-2 items-center">
        <x-inputs.text-input variant="search" type="text" name="search" value="{{ request('search') }}"
               placeholder="ユーザー名・メールアドレスで検索" class="w-72" />
        <x-buttons.search-button>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            検索
        </x-buttons.search-button>
        @if(request('search'))
            <x-buttons.link-button variant="ghost" :href="route('users.index')">クリア</x-buttons.link-button>
        @endif
    </form>
</div>

@if(session('success'))
    <div class="mb-4 flex items-center gap-2 bg-green-50 border-l-4 border-green-400 rounded-lg px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 flex items-center gap-2 bg-red-50 border-l-4 border-red-400 rounded-lg px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-800 text-slate-200">
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">ユーザー名</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">メールアドレス</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">ロール</th>
                <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($users as $user)
            <tr class="hover:bg-indigo-50 transition-colors">
                <td class="px-5 py-3.5 font-medium text-slate-800">{{ $user->name }}</td>
                <td class="px-5 py-3.5 text-slate-500">{{ $user->email }}</td>
                <td class="px-5 py-3.5">
                    @foreach($user->roles as $role)
                        <x-badges.badge variant="{{ match($role->name) { 'admin' => 'danger', 'sales' => 'primary', 'manufacture' => 'warning', default => 'default' } }}">
                            {{ match($role->name) {
                                'admin'       => '管理者',
                                'sales'       => '営業',
                                'manufacture' => '製造',
                                'warehouse'   => '倉庫',
                                default       => $role->name,
                            } }}
                        </x-badges.badge>
                    @endforeach
                </td>
                <td class="px-5 py-3.5 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <x-buttons.table-action variant="edit" :href="route('users.edit', $user)">編集</x-buttons.table-action>
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                  class="inline"
                                  onsubmit="return confirm('「{{ $user->name }}」を削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <x-buttons.table-action variant="delete">削除</x-buttons.table-action>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-12 text-center text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    ユーザーが登録されていません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
