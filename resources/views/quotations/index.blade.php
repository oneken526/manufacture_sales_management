@extends('layouts.app')

@section('title', '見積書管理')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">見積書管理</h1>
        <p class="text-sm text-slate-500 mt-0.5">見積書の確認・検索・管理</p>
    </div>
    <x-buttons.link-button :href="route('quotations.create')">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規登録
    </x-buttons.link-button>
</div>

{{-- 検索フォーム 🔵 仕様書 §4: 5条件・AND検索 --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-5">
    <form method="GET" action="{{ route('quotations.index') }}" class="flex flex-wrap gap-2 items-end">
        <div>
            <x-inputs.input-label value="見積番号" />
            <x-inputs.text-input type="text" name="quotation_number"
                value="{{ request('quotation_number') }}"
                placeholder="部分一致" class="w-40" />
        </div>
        <div>
            <x-inputs.input-label value="得意先名" />
            <x-inputs.text-input type="text" name="customer_name"
                value="{{ request('customer_name') }}"
                placeholder="部分一致" class="w-40" />
        </div>
        <div>
            <x-inputs.input-label value="ステータス" />
            <x-inputs.select name="status" class="w-36">
                <option value="">すべて</option>
                <option value="draft"    @selected(request('status') === 'draft')>下書き</option>
                <option value="pending"  @selected(request('status') === 'pending')>承認待ち</option>
                <option value="approved" @selected(request('status') === 'approved')>承認済み</option>
                <option value="rejected" @selected(request('status') === 'rejected')>却下</option>
            </x-inputs.select>
        </div>
        <div>
            <x-inputs.input-label value="作成日（開始）" />
            <x-inputs.text-input type="date" name="created_from"
                value="{{ request('created_from') }}" class="w-36" />
        </div>
        <div>
            <x-inputs.input-label value="作成日（終了）" />
            <x-inputs.text-input type="date" name="created_to"
                value="{{ request('created_to') }}" class="w-36" />
        </div>
        <x-buttons.search-button>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            検索
        </x-buttons.search-button>
        @if(request()->hasAny(['quotation_number','customer_name','status','created_from','created_to']))
            <x-buttons.link-button variant="ghost" :href="route('quotations.index')">クリア</x-buttons.link-button>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-800 text-slate-200">
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">見積番号</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">得意先名</th>
                <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wider">合計金額</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">有効期限</th>
                <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider">ステータス</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">作成者</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">作成日</th>
                <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($quotations as $quotation)
            <tr class="hover:bg-indigo-50 transition-colors">
                {{-- 見積番号: クリックで詳細画面へ 🔵 仕様書 §3 --}}
                <td class="px-5 py-3.5 font-mono text-slate-700 text-xs">
                    <a href="{{ route('quotations.show', $quotation, false) }}"
                       class="text-indigo-600 hover:text-indigo-800 hover:underline">
                        {{ $quotation->quotation_number }}
                    </a>
                </td>
                <td class="px-5 py-3.5 text-slate-700">{{ $quotation->customer->name ?? '—' }}</td>
                <td class="px-5 py-3.5 text-right text-slate-700">—</td>
                {{-- 有効期限：期限切れは赤で強調 🔵 仕様書 §3 --}}
                <td class="px-5 py-3.5 text-sm {{ $quotation->valid_until?->isPast() ? 'text-red-600 font-semibold' : 'text-slate-600' }}">
                    {{ $quotation->valid_until?->format('Y-m-d') ?? '—' }}
                </td>
                {{-- ステータスバッジ: Quotation::statusBadge() でvariant/labelを取得 🔵 仕様書 §5 --}}
                <td class="px-5 py-3.5 text-center">
                    @php $badge = $quotation->statusBadge() @endphp
                    <x-badges.badge :variant="$badge['variant']">{{ $badge['label'] }}</x-badges.badge>
                </td>
                <td class="px-5 py-3.5 text-slate-600">{{ $quotation->user->name ?? '—' }}</td>
                <td class="px-5 py-3.5 text-slate-500 text-xs">{{ $quotation->created_at->format('Y-m-d') }}</td>
                {{-- 操作ボタン: ステータスにより表示制御 🔵 仕様書 §6 --}}
                <td class="px-5 py-3.5 text-center">
                    <div class="flex items-center justify-center gap-1">
                        {{-- 詳細は常に表示 --}}
                        <x-buttons.table-action variant="edit"
                            :href="route('quotations.show', $quotation, false)">詳細</x-buttons.table-action>

                        {{-- 編集は draft のみ表示 --}}
                        @if($quotation->status === 'draft')
                            <x-buttons.table-action variant="edit"
                                :href="route('quotations.edit', $quotation, false)">編集</x-buttons.table-action>
                        @endif

                        {{-- 複製は常に表示（TASK-0016 で実装） --}}
                        <form method="POST"
                              action="{{ route('quotations.copy', $quotation) }}"
                              class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium transition-colors bg-slate-50 text-slate-700 hover:bg-slate-100">
                                複製
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    見積書が登録されていません
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $quotations->links() }}
</div>
@endsection
