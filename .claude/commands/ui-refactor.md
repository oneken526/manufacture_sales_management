---
description: Blade ビューをスキャンして重複UIパーツ（ボタン・入力・セレクト・バッジ等）を検出し、resources/views/components/ へ Blade コンポーネントとして切り出す。「UIをコンポーネント化して」「Bladeを整理したい」「重複パーツをまとめて」「/ui-refactor」などのリクエストで積極的に使用すること。
---

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

これらの条件は典型的な Tailwind バッジの構造を前提にしている。プロジェクト固有のクラス（例: `rounded-lg`）や `@apply` で抽象化されている場合は条件を緩めて「視覚的にラベルとして機能しているか」で判断すること。

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

Tailwind クラスの具体値はステップ2のスキャン結果から抽出した値を使うこと（ハードコードしない）。
`$attributes->merge()` を使って呼び出し元からクラスを追加できるようにする。

#### x-button
- `@props`: `variant`（primary / secondary / danger）、`type`（デフォルト: `'button'`）
- `@php` の `$styles` 配列で variant ごとにスキャンで得たクラスを割り当てる
- テキストは `$slot` で受け取る

#### x-input
- `@props`: `name`、`type`（デフォルト: `'text'`）、`value`、`maxlength`、`placeholder`
- `$errors->has($name)` でエラー時のボーダー色を切り替える

#### x-select
- `@props`: `name`
- 選択肢は `$slot` で受け取る
- x-input と同じボーダー・フォーカスクラスを適用する

#### x-badge
- `@props`: `variant`（default / success / warning / danger / info）
- `@php` の `$styles` 配列で variant ごとにスキャンで得た背景色・文字色を割り当てる
- テキストは `$slot` で受け取る

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
- **セクションは `components/` のサブフォルダと 1対1 に対応させる**
  - `buttons/` → **buttons** セクション
  - `inputs/` → **inputs** セクション（text-input・input-label・input-error をまとめて掲載）
  - `badges/` → **badges** セクション
  - `ui/` → **ui** セクション（dashboard-card・quick-link 等をまとめて掲載）
  - 新しいサブフォルダが増えたら同名セクションを追加する
- 各セクションには以下を含める:
  1. フォルダ名（セクション見出し）とそのフォルダに含まれるコンポーネントタグ一覧
  2. コンポーネントごとのプレビューカード（全バリアント・全パターンの実物描画）
  3. 各カードの末尾に使用例コード（`<pre><code>` タグ）

### セクション構造

- 各セクションはトグルボタンで開閉するアコーディオン形式とする
- **初期表示は閉じた状態**
- ヘッダー行にフォルダ名を表示し、クリックで本文を展開する
  - フォルダ内にサブフォルダがある場合はそのサブフォルダ名をサブテキストとして並べる
  - サブフォルダがなく `.blade.php` ファイルのみの場合はコンポーネントタグ一覧を並べる
- 既存のカタログページの実装スタイルに合わせること

### セクションの生成ルール

`resources/views/components/` 内の全 `.blade.php` を読み込み、
`@props` の内容から各バリアントを推定して実物プレビューを生成する。

- バリアントを持つコンポーネント（ボタン・バッジ等）: バリアント全種のプレビューをまとめて1カードに表示
- 入力系コンポーネント: type 別に複数パターンを並べる
- named slot（`$icon` 等）を持つコンポーネント: 代表的な SVG アイコンを渡した例を1〜3件表示

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
- `resources/views/components/buttons/button.blade.php` — props: variant, type
- `resources/views/components/inputs/input.blade.php`  — props: name, type, value, maxlength, placeholder
- ...

### 書き換えたファイル
- `resources/views/customers/create.blade.php` — ボタン×2 を置換
- `resources/views/warehouses/create.blade.php` — ボタン×2 を置換
- ...

### カタログページ
- `resources/views/ui-catalog.blade.php` を更新しました
- ブラウザで http://localhost/ui-catalog を開くと確認できます（ローカル環境のみ）
```
