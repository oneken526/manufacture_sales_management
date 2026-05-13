<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI コンポーネントカタログ</title>
    <style>[x-cloak]{display:none!important}</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ filter: null }" class="bg-slate-50 text-slate-800 min-h-screen">

{{-- ヘッダー --}}
<div class="bg-white border-b border-slate-200 px-8 py-5 mb-10 shadow-sm">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-slate-800">UI コンポーネントカタログ</h1>
        <p class="text-slate-400 text-sm mt-0.5">
            最終更新: {{ now()->format('Y-m-d H:i') }}
            &nbsp;·&nbsp;
            <span class="text-indigo-500">ローカル環境専用</span>
            &nbsp;·&nbsp;
            <span class="inline-flex items-center gap-2 text-xs">
                <button @click="filter = filter === 'form' ? null : 'form'"
                        :class="filter === 'form' ? 'bg-indigo-500 text-white' : 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100'"
                        class="px-1.5 py-0.5 rounded font-medium transition-colors cursor-pointer">フォームページ</button>
                <button @click="filter = filter === 'search' ? null : 'search'"
                        :class="filter === 'search' ? 'bg-emerald-500 text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100'"
                        class="px-1.5 py-0.5 rounded font-medium transition-colors cursor-pointer">検索バー</button>
                <button @click="filter = filter === 'list' ? null : 'list'"
                        :class="filter === 'list' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-600 hover:bg-amber-100'"
                        class="px-1.5 py-0.5 rounded font-medium transition-colors cursor-pointer">一覧ページ</button>
                <button @click="filter = filter === 'dashboard' ? null : 'dashboard'"
                        :class="filter === 'dashboard' ? 'bg-violet-500 text-white' : 'bg-violet-50 text-violet-600 hover:bg-violet-100'"
                        class="px-1.5 py-0.5 rounded font-medium transition-colors cursor-pointer">ダッシュボード</button>
            </span>
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-8 pb-20 space-y-4">

    {{-- ========== buttons/ ========== --}}
    <section x-data="{ open: false }" x-show="!filter || ['form','search','list'].includes(filter)" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-slate-50 transition-colors">
            <span class="flex items-center gap-2.5">
                <span class="inline-block w-2 h-2 rounded-full bg-indigo-500 shrink-0"></span>
                <span class="text-base font-bold text-slate-700">buttons</span>
                <span class="text-xs text-slate-400 font-mono font-normal">
                    x-buttons.primary-button &nbsp;/&nbsp; x-buttons.secondary-button &nbsp;/&nbsp; x-buttons.danger-button &nbsp;/&nbsp; x-buttons.link-button &nbsp;/&nbsp; x-buttons.search-button &nbsp;/&nbsp; x-buttons.table-action
                </span>
            </span>
            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-cloak class="border-t border-slate-100 px-6 py-5 space-y-4">
            {{-- primary --}}
            <div x-show="!filter || filter==='form'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-mono">x-buttons.primary-button</span>
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium">フォームページ</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">フォーム送信など最も重要なアクションに使う <code class="bg-slate-100 px-1 rounded">button</code> 要素。<code class="bg-slate-100 px-1 rounded">type="submit"</code> がデフォルト。</div>
                <div class="px-6 py-5 flex flex-wrap items-center gap-3">
                    <x-buttons.primary-button>登録する</x-buttons.primary-button>
                    <x-buttons.primary-button>更新する</x-buttons.primary-button>
                    <x-buttons.primary-button disabled>disabled</x-buttons.primary-button>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-buttons.primary-button&gt;登録する&lt;/x-buttons.primary-button&gt;
&lt;x-buttons.primary-button type="button"&gt;更新する&lt;/x-buttons.primary-button&gt;</code></pre>
            </div>

            {{-- secondary --}}
            <div x-show="!filter || filter==='form'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-mono">x-buttons.secondary-button</span>
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium">フォームページ</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">キャンセル・戻るなどサブアクションに使う <code class="bg-slate-100 px-1 rounded">button</code> 要素。白背景にグレーボーダーのスタイル。<code class="bg-slate-100 px-1 rounded">type="button"</code> がデフォルト。</div>
                <div class="px-6 py-5 flex flex-wrap items-center gap-3">
                    <x-buttons.secondary-button>キャンセル</x-buttons.secondary-button>
                    <x-buttons.secondary-button>戻る</x-buttons.secondary-button>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-buttons.secondary-button&gt;キャンセル&lt;/x-buttons.secondary-button&gt;</code></pre>
            </div>

            {{-- danger --}}
            <div x-show="!filter || ['form','list'].includes(filter)" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-mono">x-buttons.danger-button</span>
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium">フォームページ</span>
                    <span class="text-xs bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded font-medium">一覧ページ</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">削除・破壊的操作のフォーム送信に使う <code class="bg-slate-100 px-1 rounded">button</code> 要素。赤塗りつぶしスタイル。confirm ダイアログと組み合わせて使用する。</div>
                <div class="px-6 py-5 flex flex-wrap items-center gap-3">
                    <x-buttons.danger-button>削除する</x-buttons.danger-button>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-buttons.danger-button&gt;削除する&lt;/x-buttons.danger-button&gt;</code></pre>
            </div>

            {{-- link-button --}}
            <div x-show="!filter || ['list','search'].includes(filter)" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-slate-400 font-mono">x-buttons.link-button &nbsp;—&nbsp; props: <span class="text-slate-500">variant</span>（primary / ghost）</span>
                    <span class="text-xs bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded font-medium">一覧ページ</span>
                    <span class="text-xs bg-emerald-50 text-emerald-600 px-1.5 py-0.5 rounded font-medium">検索バー</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">ページ遷移を伴うアクションに使う <code class="bg-slate-100 px-1 rounded">a</code> 要素。<code class="bg-slate-100 px-1 rounded">variant="primary"</code> は新規登録など主アクション（indigo）、<code class="bg-slate-100 px-1 rounded">variant="ghost"</code> は検索クリアなど補助アクション（slate）。</div>
                <div class="px-6 py-5 flex flex-wrap items-center gap-3">
                    <x-buttons.link-button href="#">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        新規登録
                    </x-buttons.link-button>
                    <x-buttons.link-button variant="ghost" href="#">クリア</x-buttons.link-button>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-buttons.link-button :href="route('products.create')"&gt;
    &lt;svg class="w-4 h-4" ...&gt;...&lt;/svg&gt; 新規登録
&lt;/x-buttons.link-button&gt;
&lt;x-buttons.link-button variant="ghost" :href="route('products.index')"&gt;クリア&lt;/x-buttons.link-button&gt;</code></pre>
            </div>

            {{-- search-button --}}
            <div x-show="!filter || filter==='search'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-mono">x-buttons.search-button</span>
                    <span class="text-xs bg-emerald-50 text-emerald-600 px-1.5 py-0.5 rounded font-medium">検索バー</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">検索フォームの送信専用 <code class="bg-slate-100 px-1 rounded">button</code> 要素。落ち着いた slate-700 背景。<code class="bg-slate-100 px-1 rounded">type="submit"</code> がデフォルト。アイコンと文字を $slot で渡す。</div>
                <div class="px-6 py-5 flex flex-wrap items-center gap-3">
                    <x-buttons.search-button>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        検索
                    </x-buttons.search-button>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-buttons.search-button&gt;
    &lt;svg class="w-4 h-4" ...&gt;...&lt;/svg&gt; 検索
&lt;/x-buttons.search-button&gt;</code></pre>
            </div>

            {{-- table-action --}}
            <div x-show="!filter || filter==='list'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-slate-400 font-mono">x-buttons.table-action &nbsp;—&nbsp; props: <span class="text-slate-500">variant</span>（edit / delete）</span>
                    <span class="text-xs bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded font-medium">一覧ページ</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">テーブル行の操作ボタン。<code class="bg-slate-100 px-1 rounded">variant="edit"</code> は <code class="bg-slate-100 px-1 rounded">a</code> 要素（href 必須）、<code class="bg-slate-100 px-1 rounded">variant="delete"</code> は <code class="bg-slate-100 px-1 rounded">button</code> 要素に自動切替。ゴーストスタイルでテーブル内の視覚的ノイズを抑える。</div>
                <div class="px-6 py-5 flex flex-wrap items-center gap-3">
                    <x-buttons.table-action variant="edit" href="#">編集</x-buttons.table-action>
                    <x-buttons.table-action variant="delete">削除</x-buttons.table-action>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-buttons.table-action variant="edit" :href="route('products.edit', $product)"&gt;編集&lt;/x-buttons.table-action&gt;
&lt;x-buttons.table-action variant="delete"&gt;削除&lt;/x-buttons.table-action&gt;</code></pre>
            </div>
        </div>
    </section>

    {{-- ========== inputs/ ========== --}}
    <section x-data="{ open: false }" x-show="!filter || ['form','search'].includes(filter)" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-slate-50 transition-colors">
            <span class="flex items-center gap-2.5">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                <span class="text-base font-bold text-slate-700">inputs</span>
                <span class="text-xs text-slate-400 font-mono font-normal">
                    x-inputs.text-input &nbsp;/&nbsp; x-inputs.textarea &nbsp;/&nbsp; x-inputs.input-label &nbsp;/&nbsp; x-inputs.input-error &nbsp;/&nbsp; x-inputs.select
                </span>
            </span>
            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-cloak class="border-t border-slate-100 px-6 py-5 space-y-4">
            {{-- text-input default --}}
            <div x-show="!filter || filter==='form'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-mono">x-inputs.text-input</span>
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium">フォームページ</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">フォーム用テキスト入力。<code class="bg-slate-100 px-1 rounded">variant="default"</code>（gray-300 border、rounded-md）はフォームページで <code class="bg-slate-100 px-1 rounded">input-label</code> と組み合わせて使用。<code class="bg-slate-100 px-1 rounded">name</code> 属性を渡すと <code class="bg-slate-100 px-1 rounded">$errors</code> との連携が有効になる。</div>
                <div class="px-6 py-5 grid grid-cols-2 gap-4 max-w-lg">
                    <div>
                        <x-inputs.input-label value="テキスト" />
                        <x-inputs.text-input class="mt-1 w-full" type="text" placeholder="例：ABC-001" />
                    </div>
                    <div>
                        <x-inputs.input-label value="メールアドレス" />
                        <x-inputs.text-input class="mt-1 w-full" type="email" placeholder="example@mail.com" />
                    </div>
                    <div>
                        <x-inputs.input-label value="数値" />
                        <x-inputs.text-input class="mt-1 w-full" type="number" placeholder="0" />
                    </div>
                    <div>
                        <x-inputs.input-label value="無効状態" />
                        <x-inputs.text-input class="mt-1 w-full" type="text" placeholder="disabled" :disabled="true" />
                    </div>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-inputs.text-input name="code" :value="old('code')" /&gt;
&lt;x-inputs.text-input name="email" type="email" /&gt;
&lt;x-inputs.text-input name="price" type="number" /&gt;</code></pre>
            </div>

            {{-- input-label --}}
            <div x-show="!filter || filter==='form'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-slate-400 font-mono">x-inputs.input-label &nbsp;—&nbsp; props: <span class="text-slate-500">value</span>, <span class="text-slate-500">required</span>（bool）/ slot: 補助テキスト</span>
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium">フォームページ</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">フォームフィールドのラベル。<code class="bg-slate-100 px-1 rounded">required</code> を渡すと <code class="bg-slate-100 px-1 rounded text-red-500">*</code> が付く。<code class="bg-slate-100 px-1 rounded">$slot</code> に補助テキスト（単位・注釈）を追加できる。</div>
                <div class="px-6 py-5 space-y-3 max-w-sm">
                    <x-inputs.input-label value="備考" />
                    <x-inputs.input-label value="商品コード" :required="true" />
                    <x-inputs.input-label value="与信限度額" :required="true">
                        <span class="text-xs text-slate-400">（0 = 制限なし）</span>
                    </x-inputs.input-label>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-inputs.input-label value="備考" /&gt;
&lt;x-inputs.input-label value="商品コード" :required="true" /&gt;
&lt;x-inputs.input-label value="与信限度額" :required="true"&gt;
    &lt;span class="text-xs text-slate-400"&gt;（0 = 制限なし）&lt;/span&gt;
&lt;/x-inputs.input-label&gt;</code></pre>
            </div>

            {{-- input-error --}}
            <div x-show="!filter || filter==='form'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-mono">x-inputs.input-error &nbsp;—&nbsp; props: <span class="text-slate-500">messages</span></span>
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium">フォームページ</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">バリデーションエラーを表示。<code class="bg-slate-100 px-1 rounded">$errors->get('field')</code> を渡す。メッセージが空配列の場合は何も表示しない。<code class="bg-slate-100 px-1 rounded">input-label</code>・<code class="bg-slate-100 px-1 rounded">text-input</code> とセットで使用する。</div>
                <div class="px-6 py-5 max-w-sm space-y-1">
                    <x-inputs.input-error :messages="['得意先コードは必須です。']" />
                    <x-inputs.input-error :messages="['20文字以内で入力してください。', '使用できない文字が含まれています。']" />
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-inputs.input-label value="得意先コード" :required="true" /&gt;
&lt;x-inputs.text-input name="code" type="text" :value="old('code')" class="w-full" /&gt;
&lt;x-inputs.input-error :messages="$errors->get('code')" /&gt;</code></pre>
            </div>

            {{-- textarea --}}
            <div x-show="!filter || filter==='form'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-mono">x-inputs.textarea &nbsp;—&nbsp; props: <span class="text-slate-500">rows</span>（デフォルト: 3）</span>
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium">フォームページ</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">複数行テキスト入力。<code class="bg-slate-100 px-1 rounded">name</code> を渡すと <code class="bg-slate-100 px-1 rounded">$errors</code> に基づいてボーダー色が自動切替。<code class="bg-slate-100 px-1 rounded">text-input</code> と同じスタイル・同じエラー切替ロジックを使用。</div>
                <div class="px-6 py-5 max-w-sm">
                    <x-inputs.textarea name="sample_notes" rows="3" class="w-full"></x-inputs.textarea>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-inputs.textarea name="notes" rows="3" class="w-full"&gt;
    @{{ old('notes', $model?->notes) }}
&lt;/x-inputs.textarea&gt;</code></pre>
            </div>

            {{-- text-input variant=search --}}
            <div x-show="!filter || filter==='search'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-slate-400 font-mono">x-inputs.text-input &nbsp;—&nbsp; props: <span class="text-slate-500">variant</span>（default / search）</span>
                    <span class="text-xs bg-emerald-50 text-emerald-600 px-1.5 py-0.5 rounded font-medium">検索バー</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100"><code class="bg-slate-100 px-1 rounded">variant="search"</code> は検索バー専用スタイル（slate-300 border、rounded-lg）。検索フォーム内で <code class="bg-slate-100 px-1 rounded">search-button</code> と並べて使う。幅は <code class="bg-slate-100 px-1 rounded">class="w-64"</code> 等で指定する。</div>
                <div class="px-6 py-5 flex flex-wrap gap-3 items-center">
                    <x-inputs.text-input variant="search" type="text" placeholder="キーワードで検索" class="w-64" />
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-inputs.text-input variant="search" type="text" name="search"
    value="{{ request('search') }}" placeholder="商品名・コードで検索" class="w-64" /&gt;</code></pre>
            </div>

            {{-- select --}}
            <div x-show="!filter || ['search','form'].includes(filter)" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-mono">x-inputs.select</span>
                    <span class="text-xs bg-emerald-50 text-emerald-600 px-1.5 py-0.5 rounded font-medium">検索バー</span>
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-medium">フォームページ</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">ドロップダウン選択。<code class="bg-slate-100 px-1 rounded">name</code> を渡すとエラー時にボーダーが赤に自動切替。選択肢は <code class="bg-slate-100 px-1 rounded">$slot</code> で渡す。検索バーでは幅指定なし、フォームでは <code class="bg-slate-100 px-1 rounded">class="w-full"</code> を追加する。</div>
                <div class="px-6 py-5 flex flex-wrap gap-3 items-center">
                    <x-inputs.select name="sample">
                        <option value="">全カテゴリ</option>
                        <option value="1">カテゴリA</option>
                        <option value="2">カテゴリB</option>
                    </x-inputs.select>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-inputs.select name="category_id"&gt;
    &lt;option value=""&gt;全カテゴリ&lt;/option&gt;
    @@foreach($categories as $category)
        &lt;option value="@{{ $category->id }}" @{{ request('category_id') == $category->id ? 'selected' : '' }}&gt;
            @{{ $category->name }}
        &lt;/option&gt;
    @@endforeach
&lt;/x-inputs.select&gt;</code></pre>
            </div>
        </div>
    </section>

    {{-- ========== 追加コンポーネントはここに自動挿入される ========== --}}
    {{-- [ui-refactor:components-start] --}}

    {{-- ========== badges/ ========== --}}
    <section x-data="{ open: false }" x-show="!filter || filter==='list'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-slate-50 transition-colors">
            <span class="flex items-center gap-2.5">
                <span class="inline-block w-2 h-2 rounded-full bg-violet-500 shrink-0"></span>
                <span class="text-base font-bold text-slate-700">badges</span>
                <span class="text-xs text-slate-400 font-mono font-normal">x-badges.badge</span>
            </span>
            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-cloak class="border-t border-slate-100 px-6 py-5">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-slate-400 font-mono">x-badges.badge &nbsp;—&nbsp; props: <span class="text-slate-500">variant</span>（indigo / emerald / amber / violet / cyan / default / success / warning / danger / info）</span>
                    <span class="text-xs bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded font-medium">一覧ページ</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">ステータス・カテゴリ表示の小さなラベル。10種の variant で色分けする。テーブルセルや詳細画面のステータス列に使用。テキストは <code class="bg-slate-100 px-1 rounded">$slot</code> で渡す。</div>
                <div class="px-6 py-5 flex flex-wrap items-center gap-2">
                    <x-badges.badge variant="indigo">販売</x-badges.badge>
                    <x-badges.badge variant="emerald">在庫</x-badges.badge>
                    <x-badges.badge variant="amber">請求</x-badges.badge>
                    <x-badges.badge variant="violet">製造</x-badges.badge>
                    <x-badges.badge variant="cyan">出荷</x-badges.badge>
                    <x-badges.badge variant="default">通常</x-badges.badge>
                    <x-badges.badge variant="success">承認済</x-badges.badge>
                    <x-badges.badge variant="warning">申請中</x-badges.badge>
                    <x-badges.badge variant="danger">却下</x-badges.badge>
                    <x-badges.badge variant="info">情報</x-badges.badge>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-badges.badge variant="indigo"&gt;販売&lt;/x-badges.badge&gt;
&lt;x-badges.badge variant="emerald"&gt;在庫&lt;/x-badges.badge&gt;
&lt;x-badges.badge variant="danger"&gt;却下&lt;/x-badges.badge&gt;</code></pre>
            </div>
        </div>
    </section>

    {{-- ========== ui/ ========== --}}
    <section x-data="{ open: false }" x-show="!filter || filter==='dashboard'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-slate-50 transition-colors">
            <span class="flex items-center gap-2.5">
                <span class="inline-block w-2 h-2 rounded-full bg-cyan-500 shrink-0"></span>
                <span class="text-base font-bold text-slate-700">ui</span>
                <span class="text-xs text-slate-400 font-mono font-normal">
                    x-ui.dashboard-card &nbsp;/&nbsp; x-ui.quick-link
                </span>
            </span>
            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-cloak class="border-t border-slate-100 px-6 py-5 space-y-4">
            {{-- dashboard-card --}}
            <div x-show="!filter || filter==='dashboard'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-slate-400 font-mono">x-ui.dashboard-card &nbsp;—&nbsp; props: <span class="text-slate-500">color</span>（indigo / emerald / amber / violet / cyan）, <span class="text-slate-500">badge</span>, <span class="text-slate-500">title</span>, <span class="text-slate-500">description</span>, <span class="text-slate-500">href</span> / slot: <span class="text-slate-500">icon</span></span>
                    <span class="text-xs bg-violet-50 text-violet-600 px-1.5 py-0.5 rounded font-medium">ダッシュボード</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">ダッシュボードのメニューカード。<code class="bg-slate-100 px-1 rounded">color</code> でアクセントカラーを指定し、<code class="bg-slate-100 px-1 rounded">badge</code> に業務区分ラベル、<code class="bg-slate-100 px-1 rounded">icon</code> スロットに SVG を渡す。<code class="bg-slate-100 px-1 rounded">href</code> にルートが未定義の場合は <code class="bg-slate-100 px-1 rounded">Route::has()</code> で分岐する。</div>
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-ui.dashboard-card color="indigo" badge="販売" title="受注管理" description="受注の確認・登録・進捗管理" href="#">
                        <x-slot:icon>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </x-slot:icon>
                    </x-ui.dashboard-card>
                    <x-ui.dashboard-card color="emerald" badge="在庫" title="在庫管理" description="現在庫・引当在庫の確認" href="#">
                        <x-slot:icon>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                        </x-slot:icon>
                    </x-ui.dashboard-card>
                    <x-ui.dashboard-card color="violet" badge="製造" title="製造指示" description="製造指示の確認・作業進捗の更新" href="#">
                        <x-slot:icon>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </x-slot:icon>
                    </x-ui.dashboard-card>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-ui.dashboard-card color="indigo" badge="販売" title="受注管理"
    description="受注の確認・登録・進捗管理"
    :href="Route::has('orders.index') ? route('orders.index') : '#'"&gt;
    &lt;x-slot:icon&gt;
        &lt;svg class="w-5 h-5" ...&gt;...&lt;/svg&gt;
    &lt;/x-slot:icon&gt;
&lt;/x-ui.dashboard-card&gt;</code></pre>
            </div>

            {{-- quick-link --}}
            <div x-show="!filter || filter==='dashboard'" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-slate-400 font-mono">x-ui.quick-link &nbsp;—&nbsp; props: <span class="text-slate-500">href</span> / slots: <span class="text-slate-500">icon</span>, デフォルトスロット（テキスト）</span>
                    <span class="text-xs bg-violet-50 text-violet-600 px-1.5 py-0.5 rounded font-medium">ダッシュボード</span>
                </div>
                <div class="px-5 py-2.5 text-xs text-slate-500 border-b border-slate-100">ダッシュボードの小さなクイックリンクタイル。<code class="bg-slate-100 px-1 rounded">icon</code> スロットに SVG、デフォルトスロットにリンクテキストを渡す。<code class="bg-slate-100 px-1 rounded">dashboard-card</code> より軽量で、よく使う機能への直リンクに使用する。</div>
                <div class="px-6 py-5 grid grid-cols-2 md:grid-cols-4 gap-3">
                    <x-ui.quick-link href="#">
                        <x-slot:icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </x-slot:icon>
                        得意先管理
                    </x-ui.quick-link>
                    <x-ui.quick-link href="#">
                        <x-slot:icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </x-slot:icon>
                        商品管理
                    </x-ui.quick-link>
                    <x-ui.quick-link href="#">
                        <x-slot:icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                        </x-slot:icon>
                        倉庫管理
                    </x-ui.quick-link>
                    <x-ui.quick-link href="#">
                        <x-slot:icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </x-slot:icon>
                        ユーザー管理
                    </x-ui.quick-link>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-ui.quick-link :href="route('customers.index')"&gt;
    &lt;x-slot:icon&gt;
        &lt;svg class="w-4 h-4" ...&gt;...&lt;/svg&gt;
    &lt;/x-slot:icon&gt;
    得意先管理
&lt;/x-ui.quick-link&gt;</code></pre>
            </div>
        </div>
    </section>

    {{-- [ui-refactor:components-end] --}}

</div>
</body>
</html>
