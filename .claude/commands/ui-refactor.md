# /ui-refactor — Blade UI コンポーネント化

`resources/views/` 以下の Blade ファイルをスキャンし、重複・類似している UI パーツを検出して
`resources/views/components/` に Blade コンポーネントとして切り出す。

---

## ステップ 1: スキャン

`resources/views/` 以下の全 `.blade.php` ファイルを読み込む。
ただし `resources/views/components/` 内はスキャン対象から除外する（既にコンポーネント化済みのため）。

以下の4パターンを重点的に探す。各パターンの検出基準を満たすブロックを全て列挙する。

### パターン A — 検索フォーム
- `<form method="GET"` を含む
- テキスト入力（`name="search"` 等）＋送信ボタン が含まれる
- 1ファイルにつき1ブロックを1件としてカウント

### パターン B — フラッシュメッセージ
- `session('success')` / `session('error')` / `session('warning')` を参照する `@if` ブロック
- 色付きバナー・アラートとして表示される箇所

### パターン C — 一覧テーブル
- `<table` を含み、`<thead>` と `<tbody>` を持つ
- `@forelse` または `@foreach` で行を繰り返している
- `@empty` で「データなし」メッセージを表示している

### パターン D — 削除確認フォーム
- `@method('DELETE')` を含む `<form>`
- `onsubmit="return confirm(..."` を含む

---

## ステップ 2: 候補リストの出力

検出したブロックを以下のフォーマットで一覧表示する。

```
## 検出されたコンポーネント候補

| # | パターン | 出現ファイル | 提案コンポーネント名 | 出現回数 |
|---|---------|------------|-------------------|---------|
| 1 | 検索フォーム | customers/index, warehouses/index, ... | x-search-form | 3件 |
| 2 | フラッシュメッセージ | customers/index, warehouses/index, ... | x-flash-message | 4件 |
| 3 | 一覧テーブル | customers/index | x-data-table | 1件 |
| 4 | 削除確認フォーム | customers/index, warehouses/index, ... | x-delete-form | 3件 |
```

出現回数が1件のものも候補に含めるが、備考欄に「将来の再利用向け」と記載する。

---

## ステップ 3: ユーザーに実装する番号を確認する

AskUserQuestion ツールを使い、以下を質問する:

- **質問**: 「コンポーネント化する候補の番号を選んでください（複数選択可）」
- **選択肢**: 候補ごとに1つのオプション（「# 番号: 提案コンポーネント名（出現N件）」）
- **multiSelect: true**

---

## ステップ 4: 選択された候補をコンポーネント化する

選択された番号のコンポーネントを順番に実装する。各コンポーネントについて以下を行う。

### 4-1. コンポーネントファイルの作成

`resources/views/components/{コンポーネント名}.blade.php` を作成する。

**設計方針:**
- ファイルごとに差異がある箇所（ルート名・カラム構成・プレースホルダー等）は `@props` で受け取る
- 差異がない共通部分はハードコードする
- `@props` のデフォルト値は最も多く使われている値を採用する

**各パターンの実装ガイド:**

#### x-search-form
```blade
@props(['action', 'placeholder' => 'キーワードで検索', 'paramName' => 'search'])

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-5">
    <form method="GET" action="{{ $action }}" class="flex flex-wrap gap-2 items-center">
        <input type="text" name="{{ $paramName }}" value="{{ request($paramName) }}"
               placeholder="{{ $placeholder }}"
               class="border border-slate-300 rounded-lg px-3 py-2 text-sm w-72 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
        <button type="submit"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-700 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors">
            検索
        </button>
        @if(request($paramName))
            <a href="{{ $action }}"
               class="px-4 py-2 bg-slate-100 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                クリア
            </a>
        @endif
    </form>
</div>
```

#### x-flash-message
```blade
@props(['type' => 'success'])

@php
$styles = [
    'success' => 'bg-green-50 border-green-400 text-green-700',
    'error'   => 'bg-red-50 border-red-400 text-red-700',
    'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-700',
];
$key = $type;
@endphp

@if(session($type))
<div class="mb-4 border-l-4 rounded-lg px-4 py-3 text-sm {{ $styles[$key] ?? $styles['success'] }}">
    {{ session($type) }}
</div>
@endif
```

#### x-delete-form
```blade
@props(['action', 'label' => 'このレコード'])

<form method="POST" action="{{ $action }}"
      class="inline"
      onsubmit="return confirm('「{{ $label }}」を削除しますか？')">
    @csrf
    @method('DELETE')
    <button type="submit"
            class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
        削除
    </button>
</form>
```

#### x-data-table（スロット版）
テーブルはカラム構成がビューごとに大きく異なるため、thead・tbody をスロットで受け取る構成にする。
```blade
@props(['emptyMessage' => 'データが登録されていません'])

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full text-sm">
        {{ $header }}
        <tbody class="divide-y divide-slate-100">
            {{ $slot }}
        </tbody>
    </table>
</div>
```

### 4-2. 既存ビューの書き換え

対象ビューファイルを読み込み、該当ブロックを `<x-コンポーネント名>` タグに置き換える。
元のコードを削除して新しいタグを挿入する。

**置き換えルール:**
- 元の HTML ブロック全体（開始タグから終了タグまで）を削除
- `<x-{name} :prop="..." />` または `<x-{name} :prop="...">...</x-{name}>` に置換
- インデントは元のコードに合わせる
- 動的な値（ルート名・モデル名等）は `:prop` バインディングで渡す
- 静的な文字列は `prop="..."` で渡す

---

## ステップ 5: 完了レポートの出力

実装完了後、以下のフォーマットで結果を出力する。

```
## 完了レポート

### 作成したコンポーネント
- `resources/views/components/x-search-form.blade.php` — props: action, placeholder, paramName
- `resources/views/components/x-flash-message.blade.php` — props: type
- ...

### 書き換えたファイル
- `resources/views/customers/index.blade.php` — 検索フォーム, フラッシュメッセージ, 削除フォーム を置換
- `resources/views/warehouses/index.blade.php` — 検索フォーム, 削除フォーム を置換
- ...

### 使用例
<x-search-form :action="route('customers.index')" placeholder="得意先名・コードで検索" />
<x-flash-message />
<x-delete-form :action="route('customers.destroy', $customer)" :label="$customer->name" />
```
