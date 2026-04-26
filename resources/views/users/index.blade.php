@extends('layouts.app')

@section('title', 'ユーザー管理')

@section('content')
<style>
.user-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}
.user-page-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.user-page-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0.25rem 0 0;
}
.user-search-wrap {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex;
    gap: 0.5rem;
    align-items: center;
}
.user-search-input {
    flex: 1;
    border: 1px solid #cbd5e1;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    outline: none;
}
.user-search-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,.15);
}
.user-table-wrap {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.user-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}
.user-table thead {
    background: #f8fafc;
}
.user-table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 600;
    color: #475569;
    border-bottom: 1px solid #e2e8f0;
}
.user-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
}
.user-table tbody tr:last-child td { border-bottom: none; }
.user-table tbody tr:hover { background: #f8fafc; }
.col-actions { text-align: center; }
.row-actions { display: flex; gap: 0.5rem; justify-content: center; }
.user-table-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #94a3b8;
    font-size: 0.875rem;
}
.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    border: none;
    text-decoration: none;
    transition: opacity .15s;
}
.action-btn:hover { opacity: .85; }
.action-btn--primary { background: #4f46e5; color: #fff; }
.action-btn--secondary { background: #e2e8f0; color: #475569; }
.action-btn--remove { background: #fee2e2; color: #b91c1c; font-size: 0.8rem; padding: 0.375rem 0.75rem; }
.role-badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}
.role-badge--admin    { background: #ede9fe; color: #5b21b6; }
.role-badge--sales    { background: #dcfce7; color: #15803d; }
.role-badge--manufacture { background: #fef3c7; color: #b45309; }
.role-badge--warehouse   { background: #dbeafe; color: #1d4ed8; }
.warn-box {
    background: #fff;
    border: 1px solid #fecaca;
    border-radius: 0.5rem;
    padding: 1.25rem 1.5rem;
}
.list-footer {
    background: #f8fafc;
    padding: 0.75rem;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
</style>

<div class="user-page-header">
    <div>
        <h1 class="user-page-title">ユーザー管理</h1>
        <p class="user-page-subtitle">システムユーザーの登録・編集・ロール管理</p>
    </div>
    <a href="{{ route('users.create') }}" class="action-btn action-btn--primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規登録
    </a>
</div>

<div class="user-search-wrap">
    <form method="GET" action="{{ route('users.index') }}" style="display:flex;gap:0.5rem;align-items:center;width:100%;">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="名前・メールアドレスで検索"
               class="user-search-input">
        <button type="submit" class="action-btn action-btn--primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            検索
        </button>
        @if(request('search'))
            <a href="{{ route('users.index') }}" class="action-btn action-btn--secondary">クリア</a>
        @endif
    </form>
</div>

<div class="user-table-wrap">
    <table class="user-table">
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>ロール</th>
                <th class="col-actions">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @foreach($user->roles as $role)
                        <span class="role-badge role-badge--{{ $role->name }}">{{ $role->name }}</span>
                    @endforeach
                </td>
                <td class="col-actions">
                    <div class="row-actions">
                        <a href="{{ route('users.edit', $user) }}" class="action-btn action-btn--secondary" style="font-size:0.8rem;padding:0.375rem 0.75rem;">編集</a>
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                  onsubmit="return confirm('「{{ $user->name }}」を削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn--remove">削除</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="user-table-empty">
                    ユーザーが登録されていません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="warn-box">
    管理者ユーザーは削除できません。
</div>

<div class="list-footer">
    <span>{{ $users->total() }} 件</span>
</div>

<div class="mt-pagination">
    {{ $users->links() }}
</div>
@endsection
