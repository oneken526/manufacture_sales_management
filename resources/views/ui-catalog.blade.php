<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI コンポーネントカタログ</title>
    <style>[x-cloak]{display:none!important}</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

{{-- ヘッダー --}}
<div class="bg-white border-b border-slate-200 px-8 py-5 mb-10 shadow-sm">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-slate-800">UI コンポーネントカタログ</h1>
        <p class="text-slate-400 text-sm mt-0.5">
            最終更新: {{ now()->format('Y-m-d H:i') }}
            &nbsp;·&nbsp;
            <span class="text-indigo-500">ローカル環境専用</span>
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-8 pb-20 space-y-4">

    {{-- ========== buttons/ ========== --}}
    <section x-data="{ open: false }" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-slate-50 transition-colors">
            <span class="flex items-center gap-2.5">
                <span class="inline-block w-2 h-2 rounded-full bg-indigo-500 shrink-0"></span>
                <span class="text-base font-bold text-slate-700">buttons</span>
                <span class="text-xs text-slate-400 font-mono font-normal">
                    x-buttons.primary-button &nbsp;/&nbsp; x-buttons.secondary-button &nbsp;/&nbsp; x-buttons.danger-button
                </span>
            </span>
            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-cloak class="border-t border-slate-100 px-6 py-5 space-y-4">
            {{-- primary --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 text-xs text-slate-400 font-mono">x-buttons.primary-button</div>
                <div class="px-6 py-5 flex flex-wrap items-center gap-3">
                    <x-buttons.primary-button>登録する</x-buttons.primary-button>
                    <x-buttons.primary-button>更新する</x-buttons.primary-button>
                    <x-buttons.primary-button disabled>disabled</x-buttons.primary-button>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-buttons.primary-button&gt;登録する&lt;/x-buttons.primary-button&gt;
&lt;x-buttons.primary-button type="button"&gt;更新する&lt;/x-buttons.primary-button&gt;</code></pre>
            </div>

            {{-- secondary --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 text-xs text-slate-400 font-mono">x-buttons.secondary-button</div>
                <div class="px-6 py-5 flex flex-wrap items-center gap-3">
                    <x-buttons.secondary-button>キャンセル</x-buttons.secondary-button>
                    <x-buttons.secondary-button>戻る</x-buttons.secondary-button>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-buttons.secondary-button&gt;キャンセル&lt;/x-buttons.secondary-button&gt;</code></pre>
            </div>

            {{-- danger --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 text-xs text-slate-400 font-mono">x-buttons.danger-button</div>
                <div class="px-6 py-5 flex flex-wrap items-center gap-3">
                    <x-buttons.danger-button>削除する</x-buttons.danger-button>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-buttons.danger-button&gt;削除する&lt;/x-buttons.danger-button&gt;</code></pre>
            </div>
        </div>
    </section>

    {{-- ========== inputs/ ========== --}}
    <section x-data="{ open: false }" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-slate-50 transition-colors">
            <span class="flex items-center gap-2.5">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                <span class="text-base font-bold text-slate-700">inputs</span>
                <span class="text-xs text-slate-400 font-mono font-normal">
                    x-inputs.text-input &nbsp;/&nbsp; x-inputs.input-label &nbsp;/&nbsp; x-inputs.input-error
                </span>
            </span>
            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-cloak class="border-t border-slate-100 px-6 py-5 space-y-4">
            {{-- text-input --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 text-xs text-slate-400 font-mono">x-inputs.text-input</div>
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

            {{-- input-label / input-error --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 text-xs text-slate-400 font-mono">x-inputs.input-label &nbsp;/&nbsp; x-inputs.input-error</div>
                <div class="px-6 py-5 space-y-3 max-w-sm">
                    <div>
                        <x-inputs.input-label value="倉庫コード" />
                        <x-inputs.text-input class="mt-1 w-full" type="text" value="WH-001" />
                    </div>
                    <div>
                        <x-inputs.input-label value="倉庫名（エラー例）" />
                        <x-inputs.text-input class="mt-1 w-full border-red-500" type="text" value="" />
                        <x-inputs.input-error :messages="['倉庫名は必須です。']" class="mt-1" />
                    </div>
                </div>
                <pre class="bg-slate-900 text-green-300 text-xs px-6 py-4 overflow-x-auto"><code>&lt;x-inputs.input-label value="倉庫コード" /&gt;
&lt;x-inputs.text-input name="code" :value="old('code')" /&gt;
&lt;x-inputs.input-error :messages="$errors->get('code')" /&gt;</code></pre>
            </div>
        </div>
    </section>

    {{-- ========== 追加コンポーネントはここに自動挿入される ========== --}}
    {{-- [ui-refactor:components-start] --}}

    {{-- ========== badges/ ========== --}}
    <section x-data="{ open: false }" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
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
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 text-xs text-slate-400 font-mono">x-badges.badge</div>
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
    <section x-data="{ open: false }" class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
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
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 text-xs text-slate-400 font-mono">
                    x-ui.dashboard-card &nbsp;—&nbsp;
                    props: <span class="text-slate-500">color</span>（indigo / emerald / amber / violet / cyan）, <span class="text-slate-500">badge</span>, <span class="text-slate-500">title</span>, <span class="text-slate-500">description</span>, <span class="text-slate-500">href</span> / slot: <span class="text-slate-500">icon</span>
                </div>
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
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 text-xs text-slate-400 font-mono">
                    x-ui.quick-link &nbsp;—&nbsp;
                    props: <span class="text-slate-500">href</span> / slots: <span class="text-slate-500">icon</span>, デフォルトスロット（テキスト）
                </div>
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
