# /ui-refactor — Blade UI コンポーネント化

`resources/views/` 以下の Blade ファイルをスキャンし、重複・類似している UI パーツを検出して
`resources/views/components/` に Blade コンポーネントとして切り出す。

コンポーネントはパーツの種類ごとにサブフォルダで管理する:

| フォルダ | 対象パーツ | タグ形式 |
|---------|-----------|---------|
| `buttons/` | ボタン類 | `<x-buttons.xxx>` |
| `inputs/` | テキスト入力・ラベル・エラー | `<x-inputs.xxx>` |
| `navigation/` | ナビゲーション・ドロップダウン | `<x-navigation.xxx>` |
| `ui/` | モーダル・ロゴ・その他 UI | `<x-ui.xxx>` |
| `badges/` | バッジ・ステータスラベル | `<x-badges.xxx>` |

---

## ステップ 1: 既存コンポーネントの把握

まず `resources/views/components/` 以下の全 `.blade.php` ファイルを読み込み、
各コンポーネントの **名前・@props 定義・HTML 構造** を把握する。

存在しない場合（ディレクトリが空・存在しない）はこのステップをスキップする。

---

## ステップ 2: スキャン

`resources/views/` 以下の全 `.blade.php` ファイルを読み込む。
ただし `resources/views/components/` 内はスキャン対象から除外する（既にコンポーネント化済みのため）。

以下の4種類の UI パーツを探す。**見た目の役割が一致するもの**を同一パターンとみなす
（Tailwind クラスが多少異なっていても、「primary ボタン」「danger ボタン」のような役割で分類する）。

### パターン A — ボタン

役割ごとに分類する。

| 役割 | 見分け方の目安 |
|------|-------------|
| primary（主アクション） | `bg-indigo-*` / `bg-blue-*` 系の背景色 |
| secondary（キャンセル等） | `bg-slate-*` / `bg-gray-*` 系の背景色 |
| danger（削除等） | `bg-red-*` 系の背景色 |
| link 風ボタン | `<a>` タグだが `px-* py-*` でボタン風に装飾されているもの |

検出対象: `<button` タグ、およびボタン風の `<a>` タグ。
1要素を1件としてカウントし、ファイル名・行の内容・役割を記録する。

### パターン B — テキスト入力

- `<input type="text"` / `type="email"` / `type="number"` / `type="password"` / `<textarea`
- `class` に `border` と `rounded` を含むもの（スタイル付き入力）

同じスタイルのものをまとめて「共通の入力スタイル」として1候補にする。

### パターン C — セレクトボックス

- `<select` タグで `class` に `border` と `rounded` を含むもの

### パターン D — バッジ・ラベル

- `<span` または `<div` で以下を**すべて**含むもの:
  - `inline-flex` または `inline-block`
  - `px-*` `py-*` のパディング
  - `rounded-full` または `rounded`
  - 色付き背景（`bg-*-*`）と色付きテキスト（`text-*-*`）
- ステータス表示・カテゴリ表示など「ラベル」として使われているもの

---

## ステップ 3: 候補リストの出力

検出したブロックをステップ1で把握した既存コンポーネントと照合し、
**「既存コンポーネントへの置き換え」** と **「新規コンポーネントの作成」** を区別して一覧表示する。

### 照合ルール

- 既存コンポーネントと HTML 構造・クラス・目的が **概ね一致する** → 種別を「置き換え」とする
- 既存コンポーネントに近いが **props の追加が必要** → 種別を「置き換え（props 追加要）」とする
- 対応する既存コンポーネントが存在しない → 種別を「新規作成」とする

### 出力フォーマット

```
## 検出されたコンポーネント候補

| # | パターン | 役割 | 出現ファイル | 提案コンポーネント名 | 種別 | 出現回数 |
|---|---------|------|------------|-------------------|------|---------|
| 1 | ボタン | primary | customers/create, warehouses/create, ... | x-button | 新規作成 | 5件 |
| 2 | ボタン | danger | customers/index, warehouses/index, ...   | x-button | 新規作成 | 4件 |
| 3 | テキスト入力 | — | customers/_form, warehouses/_form, ...   | x-input  | 新規作成 | 8件 |
| 4 | セレクトボックス | — | customers/_form | x-select | 新規作成 | 2件 |
| 5 | バッジ | ステータス | products/index | x-badge  | 新規作成 | 3件 |
```

- 同じ役割（例: primary ボタン）は複数ファイルにまたがっていても1候補にまとめる
- 「置き換え」: 既存コンポーネントがそのまま使える。ビューを書き換えるだけ。
- 「置き換え（props 追加要）」: 既存コンポーネントに props を追加してからビューを書き換える。
- 「新規作成」: コンポーネントファイルを新規作成してからビューを書き換える。
- 出現回数が1件のものも候補に含めるが、備考欄に「将来の再利用向け」と記載する。

---

## ステップ 4: ユーザーに実装する番号を確認する

AskUserQuestion ツールを使い、以下を質問する:

- **質問**: 「コンポーネント化する候補の番号を選んでください（複数選択可）」
- **選択肢**: 候補ごとに1つのオプション（「# 番号: 提案コンポーネント名（出現N件）」）
- **multiSelect: true**

---

## ステップ 5: 選択された候補をコンポーネント化する

選択された番号のコンポーネントを順番に実装する。**種別によって作業内容が異なる。**

### 5-0. 種別ごとの作業分岐

| 種別 | コンポーネントファイル | ビュー書き換え |
|------|----------------------|--------------|
| 置き換え | 変更なし | `<x-xxx>` タグに書き換え |
| 置き換え（props 追加要） | 既存ファイルに `@props` の項目を追加 | `<x-xxx>` タグに書き換え |
| 新規作成 | 新規ファイルを作成 | `<x-xxx>` タグに書き換え |

### 5-1. コンポーネントファイルの作成・更新

パーツ種別に応じたサブフォルダに作成する。

| 作成するコンポーネント | 保存先パス |
|----------------------|-----------|
| x-buttons.button 等 | `resources/views/components/buttons/button.blade.php` |
| x-inputs.input 等 | `resources/views/components/inputs/input.blade.php` |
| x-badges.badge 等 | `resources/views/components/badges/badge.blade.php` |

**設計方針:**
- ファイルごとに差異がある箇所（テキスト・型・役割等）は `@props` で受け取る
- 差異がない共通の Tailwind クラス群はハードコードする
- `@props` のデフォルト値は最も多く使われている値を採用する
- ボタンなどテキストを持つ要素は `$slot` でテキストを受け取る

**各パーツの実装ガイド:**

#### x-button
役割（variant）によってスタイルを切り替える。テキストは `$slot` で受け取る。
```blade
@props(['variant' => 'primary', 'type' => 'button'])

@php
$styles = [
    'primary'   => 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm',
    'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
    'danger'    => 'bg-red-50 text-red-600 hover:bg-red-100',
];
@endphp

<button type="{{ $type }}"
        {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-5 py-2 text-sm font-medium rounded-lg transition-colors ' . ($styles[$variant] ?? $styles['primary'])]) }}>
    {{ $slot }}
</button>
```

使用例:
```blade
<x-button variant="primary" type="submit">登録する</x-button>
<x-button variant="secondary" tag="a" :href="route('warehouses.index')">キャンセル</x-button>
<x-button variant="danger" type="submit">削除</x-button>
```

#### x-input
テキスト系 input（text / email / number / password）を統一する。
```blade
@props(['name', 'type' => 'text', 'value' => '', 'maxlength' => null, 'placeholder' => null])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ $value }}"
    @if($maxlength) maxlength="{{ $maxlength }}" @endif
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    {{ $attributes->merge(['class' => 'w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 ' . ($errors->has($name) ? 'border-red-400' : 'border-slate-300')]) }}>
```

使用例:
```blade
<x-input name="code" :value="old('code', $warehouse?->code)" maxlength="50" />
<x-input name="email" type="email" :value="old('email')" />
```

#### x-select
```blade
@props(['name'])

<select name="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 ' . ($errors->has($name) ? 'border-red-400' : 'border-slate-300')]) }}>
    {{ $slot }}
</select>
```

使用例:
```blade
<x-select name="closing_day">
    @foreach(range(1, 28) as $day)
        <option value="{{ $day }}">{{ $day }}日</option>
    @endforeach
</x-select>
```

#### x-badge
役割（variant）で色を切り替える。
```blade
@props(['variant' => 'default'])

@php
$styles = [
    'default' => 'bg-slate-100 text-slate-600',
    'success' => 'bg-green-100 text-green-700',
    'warning' => 'bg-yellow-100 text-yellow-700',
    'danger'  => 'bg-red-100 text-red-600',
    'info'    => 'bg-indigo-100 text-indigo-700',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . ($styles[$variant] ?? $styles['default'])]) }}>
    {{ $slot }}
</span>
```

使用例:
```blade
<x-badge variant="success">承認済</x-badge>
<x-badge variant="warning">申請中</x-badge>
```

### 5-2. 既存ビューの書き換え

対象ビューファイルを読み込み、該当ブロックを `<x-コンポーネント名>` タグに置き換える。
元のコードを削除して新しいタグを挿入する。

**置き換えルール:**
- 元の HTML ブロック全体（開始タグから終了タグまで）を削除
- `<x-{name} :prop="..." />` または `<x-{name} :prop="...">...</x-{name}>` に置換
- インデントは元のコードに合わせる
- 動的な値（ルート名・モデル名等）は `:prop` バインディングで渡す
- 静的な文字列は `prop="..."` で渡す

---

## ステップ 6: カタログページの更新

`resources/views/ui-catalog.blade.php` を生成（または上書き）する。

### カタログビューの構造

- `@extends('layouts.app')` を使わず、独立したレイアウトとする
- ページタイトル: `UI コンポーネントカタログ`
- 各コンポーネントを **セクション単位** で表示する
- 各セクションには以下を含める:
  1. コンポーネント名（`x-button` など）
  2. 全バリアント・全パターンの実物プレビュー（実際に `<x-*>` タグを使って描画）
  3. 使用例コード（`<pre><code>` タグで表示）

### 生成するビューのテンプレート

```blade
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>UI コンポーネントカタログ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 p-8">

<h1 class="text-3xl font-bold mb-2">UI コンポーネントカタログ</h1>
<p class="text-slate-500 text-sm mb-10">最終更新: {{ now()->format('Y-m-d H:i') }}</p>

{{-- コンポーネントごとのセクションをここに生成 --}}

</body>
</html>
```

### セクションの生成ルール

`resources/views/components/` 内の全 `.blade.php` を読み込み、
`@props` の内容から各バリアントを推定して実物プレビューを生成する。

**ボタン系コンポーネント（`$slot` を持つもの）:**
```blade
<section class="mb-12">
    <h2 class="text-xl font-bold mb-1">x-button</h2>
    <p class="text-slate-500 text-sm mb-4">props: variant（primary / secondary / danger）</p>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 flex flex-wrap gap-3 mb-4">
        <x-button variant="primary" type="button">登録する</x-button>
        <x-button variant="secondary" type="button">キャンセル</x-button>
        <x-button variant="danger" type="button">削除</x-button>
    </div>
    <pre class="bg-slate-800 text-green-300 text-xs rounded-lg p-4 overflow-x-auto"><code>&lt;x-button variant="primary" type="submit"&gt;登録する&lt;/x-button&gt;
&lt;x-button variant="secondary"&gt;キャンセル&lt;/x-button&gt;
&lt;x-button variant="danger" type="submit"&gt;削除&lt;/x-button&gt;</code></pre>
</section>
```

**入力系コンポーネント（`x-input`, `x-select`）:**
```blade
<section class="mb-12">
    <h2 class="text-xl font-bold mb-1">x-input</h2>
    <p class="text-slate-500 text-sm mb-4">props: name, type（text / email / number）, value, maxlength, placeholder</p>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 grid grid-cols-2 gap-4 mb-4 max-w-xl">
        <x-input name="_preview_text" placeholder="テキスト入力" />
        <x-input name="_preview_email" type="email" placeholder="メールアドレス" />
        <x-input name="_preview_number" type="number" placeholder="数値入力" />
    </div>
    <pre ...><code>...</code></pre>
</section>
```

**バッジ系コンポーネント（`x-badge`）:**
```blade
<section class="mb-12">
    <h2 class="text-xl font-bold mb-1">x-badge</h2>
    <p class="text-slate-500 text-sm mb-4">props: variant（default / success / warning / danger / info）</p>
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 flex flex-wrap gap-2 mb-4">
        <x-badge variant="default">通常</x-badge>
        <x-badge variant="success">承認済</x-badge>
        <x-badge variant="warning">申請中</x-badge>
        <x-badge variant="danger">却下</x-badge>
        <x-badge variant="info">情報</x-badge>
    </div>
    <pre ...><code>...</code></pre>
</section>
```

**既存の Breeze コンポーネント（`x-primary-button` 等）が存在する場合も同様にセクションを追加する。**

### カタログへの挿入方法

`resources/views/ui-catalog.blade.php` 内の以下のコメントを挿入ポイントとして使う。

```blade
{{-- [ui-refactor:components-start] --}}
{{-- [ui-refactor:components-end] --}}
```

新しいコンポーネントのセクション HTML をこの2行の**間に追記**する（既存セクションは消さない）。
コメント行自体は残したままにする。

---

## ステップ 7: 完了レポートの出力

実装完了後、以下のフォーマットで結果を出力する。

```
## 完了レポート

### 作成したコンポーネント
- `resources/views/components/button.blade.php` — props: variant, type
- `resources/views/components/input.blade.php`  — props: name, type, value, maxlength, placeholder
- ...

### 書き換えたファイル
- `resources/views/customers/create.blade.php` — ボタン×2 を置換
- `resources/views/warehouses/create.blade.php` — ボタン×2 を置換
- ...

### カタログページ
- `resources/views/ui-catalog.blade.php` を更新しました
- ブラウザで http://localhost/ui-catalog を開くと確認できます（ローカル環境のみ）
```
