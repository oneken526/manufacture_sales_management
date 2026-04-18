<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI コンポーネントカタログ</title>
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

<div class="max-w-4xl mx-auto px-8 pb-20 space-y-14">

    {{-- ========== Buttons ========== --}}
    <section>
        <h2 class="text-lg font-bold text-slate-700 mb-1 flex items-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
            Buttons
        </h2>
        <p class="text-slate-400 text-xs mb-5 pl-4">
            <code>x-buttons.primary-button</code> &nbsp;/&nbsp;
            <code>x-buttons.secondary-button</code> &nbsp;/&nbsp;
            <code>x-buttons.danger-button</code>
        </p>

        {{-- primary --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mb-4">
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
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mb-4">
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
    </section>

    {{-- ========== Inputs ========== --}}
    <section>
        <h2 class="text-lg font-bold text-slate-700 mb-1 flex items-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
            Inputs
        </h2>
        <p class="text-slate-400 text-xs mb-5 pl-4"><code>x-inputs.text-input</code></p>

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
    </section>

    {{-- ========== Labels ========== --}}
    <section>
        <h2 class="text-lg font-bold text-slate-700 mb-1 flex items-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span>
            Labels
        </h2>
        <p class="text-slate-400 text-xs mb-5 pl-4"><code>x-inputs.input-label</code> &nbsp;/&nbsp; <code>x-inputs.input-error</code></p>

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-2 bg-slate-50 border-b border-slate-100 text-xs text-slate-400 font-mono">x-inputs.input-label / x-inputs.input-error</div>
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
    </section>

    {{-- ========== 追加コンポーネントはここに自動挿入される ========== --}}
    {{-- [ui-refactor:components-start] --}}
    {{-- [ui-refactor:components-end] --}}

</div>
</body>
</html>
