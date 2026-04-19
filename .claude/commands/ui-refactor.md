---
description: Blade ビューをスキャンして重複UIパーツ（ボタン・入力・セレクト・バッジ等）を検出し、resources/views/components/ へ Blade コンポーネントとして切り出す。「UIをコンポーネント化して」「Bladeを整理したい」「重複パーツをまとめて」「/ui-refactor」などのリクエストで積極的に使用すること。
---

# /ui-refactor — Blade UI コンポーネント化

`resources/views/` 以下の Blade ファイルをスキャンし、重複・類似している UI パーツを検出して
`resources/views/components/` に Blade コンポーネントとして切り出す。

**前提:** このプロジェクトは Tailwind CSS を使わず、`resources/css/app.css` に定義された
セマンティック CSS クラスを使用している。コンポーネントは Tailwind クラスではなく
これらのセマンティッククラスを出力すること。

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

以下の **8種類** の UI パーツを探す。**見た目の役割が一致するもの**を同一パターンとみなす。

- **パターン A〜D**: セマンティッククラス（`app.css` の共通クラス）を使っているもの
- **パターン E〜H**: セマンティッククラスを使っていないもの（カスタムクラス・インラインスタイル）

---

### パターン A — ボタン（セマンティック）

セマンティッククラスで役割ごとに分類する。

| 役割 | 見分け方 |
|------|---------|
| primary（主アクション） | `class` に `btn-primary` を含む |
| secondary（キャンセル等） | `class` に `btn-secondary` を含む |
| dark（検索等） | `class` に `btn-dark` を含む |
| danger（削除等） | `class` に `btn-danger` を含む |
| edit（編集） | `class` に `btn-edit` を含む |
| delete（削除・小） | `class` に `btn-delete` を含む |
| link 風ボタン | `<a>` タグで `class` に `btn` を含むもの |

検出対象: `<button` タグ、およびボタン風の `<a>` タグ。
1要素を1件としてカウントし、ファイル名・行の内容・役割を記録する。

### パターン B — テキスト入力（セマンティック）

- `<input`・`<textarea` タグで `class` に `form-control` を含むもの
- エラー状態: `form-control--error` の有無も記録する

同じスタイルのものをまとめて「共通の入力スタイル」として1候補にする。

### パターン C — セレクトボックス（セマンティック）

- `<select` タグで `class` に `form-control` を含むもの

### パターン D — バッジ・ラベル（セマンティック）

- `<span` または `<div` で `class` に `badge` を含むもの
- バリアント（`badge-primary`・`badge-secondary`・`badge-none` 等）を記録する

---

### パターン E — ボタン（カスタムクラス）

セマンティッククラス（`btn-*`）を使っていないが、ボタンとして機能している要素を検出する。

検出条件（**いずれか**に該当するもの）:
- `<button` タグで `class` 属性が存在するが `btn` を含まないもの
- `<a` タグで `href` 属性を持ち、`class` 属性が存在するが `btn` を含まないもの（ただし `sidebar-link` 等のナビゲーション用クラスは除外）
- `<input type="submit"` または `<input type="button"` で `class` に `form-control` 以外のクラスが指定されているもの

記録する情報: ファイル名・行番号・タグ内容・使われているクラス名・推定役割（送信/キャンセル/削除等）。

### パターン F — テキスト入力・セレクト（カスタムクラス）

セマンティッククラス（`form-control`）を使っていない入力系要素を検出する。

検出条件:
- `<input` タグで `class` 属性が存在するが `form-control` を含まないもの  
  （`type="hidden"` `type="checkbox"` `type="radio"` `type="submit"` `type="button"` は除外）
- `<textarea` タグで `class` 属性が存在するが `form-control` を含まないもの
- `<select` タグで `class` 属性が存在するが `form-control` を含まないもの

記録する情報: ファイル名・行番号・タグ内容・使われているクラス名。

### パターン G — インラインスタイル

`style` 属性を持つ要素を検出する。

検出対象タグ: `<button`・`<a`・`<input`・`<textarea`・`<select`・`<span`・`<div`  
検出条件: `style="..."` 属性が存在するもの（値が空でない）  
除外: レイアウト調整用の1プロパティのみ（例: `style="width:100%"` など）はスキップして構わない

記録する情報: ファイル名・行番号・タグ内容・`style` の値・推定される役割（ボタン/入力/バッジ等）。

### パターン H — クロスファイル重複クラス

複数のファイルにまたがって同じ独自クラス名が繰り返し使われているケースを検出する。

検出手順:
1. パターン E・F で検出した全クラス名を収集する
2. **2ファイル以上**に登場する同一クラス名を「重複クラス」として記録する
3. そのクラスが使われているタグ種別（button/input/select/span 等）を記録する

重複数が多いほど「コンポーネント化の優先度が高い」とみなす。

---

## ステップ 3: 候補リストの出力

検出したブロックをステップ1で把握した既存コンポーネントと照合し、
**「既存コンポーネントへの置き換え」** と **「新規コンポーネントの作成」** を区別して一覧表示する。

### 照合ルール

- 既存コンポーネントと HTML 構造・クラス・目的が **概ね一致する** → 種別を「置き換え」とする
- 既存コンポーネントに近いが **props の追加が必要** → 種別を「置き換え（props 追加要）」とする
- 対応する既存コンポーネントが存在しない → 種別を「新規作成」とする

### 出力フォーマット

出力は **2セクション** に分けて表示する。

#### セクション 1: セマンティッククラス使用ファイル（パターン A〜D）

```
## 検出されたコンポーネント候補（共通CSS使用）

| # | パターン | 役割 | CSS種別 | 出現ファイル | 提案コンポーネント名 | 種別 | 出現回数 |
|---|---------|------|--------|------------|-------------------|------|---------|
| 1 | ボタン | primary | セマンティック | customers/create, warehouses/create, ... | x-buttons.button | 新規作成 | 5件 |
| 2 | ボタン | danger | セマンティック | customers/index, warehouses/index, ...   | x-buttons.button | 新規作成 | 4件 |
| 3 | テキスト入力 | — | セマンティック | customers/_form, warehouses/_form, ...   | x-inputs.input   | 新規作成 | 8件 |
| 4 | セレクトボックス | — | セマンティック | customers/_form | x-inputs.select  | 新規作成 | 2件 |
| 5 | バッジ | secondary | セマンティック | products/index | x-badges.badge   | 新規作成 | 3件 |
```

#### セクション 2: カスタムCSS・インラインスタイル使用ファイル（パターン E〜H）

```
## 検出されたコンポーネント候補（カスタムCSS — 共通化推奨）

| # | パターン | CSS種別 | クラス名/style値 | 出現ファイル | 出現回数 | 推奨対応 |
|---|---------|--------|----------------|------------|---------|---------|
| 6 | ボタン（カスタム） | カスタムクラス | .user-submit-btn | users/create, users/edit | 2件 | セマンティッククラスに統一 |
| 7 | テキスト入力（カスタム） | カスタムクラス | .user-input-field | users/_form | 3件 | セマンティッククラスに統一 |
| 8 | ボタン（インライン） | インラインスタイル | background:#4f46e5;... | users/index | 1件 | セマンティッククラスに統一 |
| 9 | 重複クラス（E+F集計） | カスタムクラス | .user-input-field | 2ファイル以上 | 5件 | 要共通化 |
```

セクション 2 の各候補には以下の注記を付ける:
> ⚠️ このファイルは `app.css` の共通セマンティッククラスを使用していません。
> コンポーネント化の前に、まずクラスを `form-control` / `btn-primary` 等に統一することを推奨します。

---

- 同じ役割（例: primary ボタン）は複数ファイルにまたがっていても1候補にまとめる
- 「置き換え」: 既存コンポーネントがそのまま使える。ビューを書き換えるだけ。
- 「置き換え（props 追加要）」: 既存コンポーネントに props を追加してからビューを書き換える。
- 「新規作成」: コンポーネントファイルを新規作成してからビューを書き換える。
- 出現回数が1件のものも候補に含めるが、備考欄に「将来の再利用向け」と記載する。
- セクション 2 の候補を選択した場合、ステップ 5 の実装前にカスタムクラスをセマンティッククラスへ書き換えてからコンポーネント化する。

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
| x-inputs.select 等 | `resources/views/components/inputs/select.blade.php` |
| x-badges.badge 等 | `resources/views/components/badges/badge.blade.php` |

**設計方針:**
- ファイルごとに差異がある箇所（テキスト・型・役割等）は `@props` で受け取る
- 出力するクラスは `resources/css/app.css` に定義されたセマンティッククラスを使う（Tailwindクラスは使わない）
- `@props` のデフォルト値は最も多く使われている値を採用する
- ボタンなどテキストを持つ要素は `$slot` でテキストを受け取る

**各パーツの実装ガイド:**

`$attributes->merge()` を使って呼び出し元からクラスを追加できるようにする。

#### x-buttons.button

```blade
@props([
    'variant' => 'primary',  // primary | secondary | dark | danger | edit | delete
    'type'    => 'button',
    'size'    => '',          // '' | 'sm'
    'href'    => null,
])

@php
    $sizeClass = $size === 'sm' ? ' btn-sm' : '';
    $classes   = "btn btn-{$variant}{$sizeClass}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
```

- `variant` で `btn-primary` / `btn-secondary` 等を切り替える
- `href` を渡すと `<a>` タグになる
- `size="sm"` で `btn-sm` を追加する

#### x-inputs.input

```blade
@props([
    'name',
    'type'        => 'text',
    'value'       => '',
    'maxlength'   => null,
    'placeholder' => null,
    'rows'        => null,   // textarea用
])

@php
    $hasError  = $errors->has($name);
    $classes   = 'form-control' . ($hasError ? ' form-control--error' : '');
@endphp

@if($type === 'textarea')
    <textarea name="{{ $name }}"
              rows="{{ $rows ?? 3 }}"
              {{ $attributes->merge(['class' => $classes]) }}>{{ old($name, $value) }}</textarea>
@else
    <input type="{{ $type }}"
           name="{{ $name }}"
           value="{{ old($name, $value) }}"
           @if($maxlength) maxlength="{{ $maxlength }}" @endif
           @if($placeholder) placeholder="{{ $placeholder }}" @endif
           {{ $attributes->merge(['class' => $classes]) }}>
@endif

@error($name)
    <p class="form-error">{{ $message }}</p>
@enderror
```

#### x-inputs.select

```blade
@props(['name'])

@php
    $hasError = $errors->has($name);
    $classes  = 'form-control' . ($hasError ? ' form-control--error' : '');
@endphp

<select name="{{ $name }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</select>

@error($name)
    <p class="form-error">{{ $message }}</p>
@enderror
```

#### x-badges.badge

```blade
@props([
    'variant' => 'secondary',  // secondary | primary | none
])

<span {{ $attributes->merge(['class' => "badge badge-{$variant}"]) }}>{{ $slot }}</span>
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

**置き換え例:**

```blade
{{-- 変換前 --}}
<button type="submit" class="btn btn-primary">登録する</button>

{{-- 変換後 --}}
<x-buttons.button type="submit" variant="primary">登録する</x-buttons.button>
```

```blade
{{-- 変換前 --}}
<input type="text" name="code" value="{{ old('code') }}"
       class="form-control {{ $errors->has('code') ? 'form-control--error' : '' }}">
@error('code')
    <p class="form-error">{{ $message }}</p>
@enderror

{{-- 変換後 --}}
<x-inputs.input name="code" :value="old('code')" />
```

```blade
{{-- 変換前 --}}
<span class="badge badge-primary">{{ $count }} 件</span>

{{-- 変換後 --}}
<x-badges.badge variant="primary">{{ $count }} 件</x-badges.badge>
```

---

## ステップ 6: カタログページの更新

`resources/views/ui-catalog.blade.php` を生成（または上書き）する。

### カタログビューの構造

- `@extends('layouts.app')` を使わず、独立したレイアウトとする
- `resources/css/app.css` を `@vite` で読み込む
- ページタイトル: `UI コンポーネントカタログ`
- **セクションは `components/` のサブフォルダと 1対1 に対応させる**
  - `buttons/` → **buttons** セクション
  - `inputs/` → **inputs** セクション
  - `badges/` → **badges** セクション
  - `ui/` → **ui** セクション
  - 新しいサブフォルダが増えたら同名セクションを追加する
- 各セクションには以下を含める:
  1. フォルダ名（セクション見出し）とそのフォルダに含まれるコンポーネントタグ一覧
  2. コンポーネントごとのプレビューカード（全バリアント・全パターンの実物描画）
  3. 各カードの末尾に使用例コード（`<pre><code>` タグ）
- カタログページ自体のスタイルも `app.css` のセマンティッククラスを使う（Tailwindクラス禁止）

### セクション構造

- 各セクションはトグルボタンで開閉するアコーディオン形式とする
- **初期表示は閉じた状態**
- ヘッダー行にフォルダ名を表示し、クリックで本文を展開する
- 既存のカタログページの実装スタイルに合わせること

### セクションの生成ルール

`resources/views/components/` 内の全 `.blade.php` を読み込み、
`@props` の内容から各バリアントを推定して実物プレビューを生成する。

- バリアントを持つコンポーネント（ボタン・バッジ等）: バリアント全種のプレビューをまとめて1カードに表示
- 入力系コンポーネント: type 別に複数パターンを並べる
- named slot（`$icon` 等）を持つコンポーネント: 代表的な内容を渡した例を1〜3件表示

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
- `resources/views/components/buttons/button.blade.php` — props: variant, type, size, href
- `resources/views/components/inputs/input.blade.php`   — props: name, type, value, maxlength, placeholder, rows
- `resources/views/components/inputs/select.blade.php`  — props: name
- `resources/views/components/badges/badge.blade.php`   — props: variant
- ...

### 書き換えたファイル
- `resources/views/customers/create.blade.php` — ボタン×2 を置換
- `resources/views/warehouses/create.blade.php` — ボタン×2 を置換
- ...

### カタログページ
- `resources/views/ui-catalog.blade.php` を更新しました
- ブラウザで http://localhost/ui-catalog を開くと確認できます（ローカル環境のみ）
```
