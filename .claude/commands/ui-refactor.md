# /ui-refactor — Blade UI コンポーネント化

`resources/views/` 以下の Blade ファイルをスキャンし、重複・類似している UI パーツを検出して
`resources/views/components/` に Blade コンポーネントとして切り出す。

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

`resources/views/components/{コンポーネント名}.blade.php` を作成する。

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

## ステップ 6: 完了レポートの出力

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
