---
description: Blade ビューと app.css を横断分析し、バラバラなカスタムクラスをセマンティッククラスに統一・重複スタイルを共通クラスとして抽出する。「CSSを整理して」「クラス名を統一して」「重複スタイルをまとめて」「/css-unify」などのリクエストで積極的に使用すること。
---

# /css-unify — CSS クラス共通化

Blade ビューと `resources/css/app.css` を横断スキャンし、以下の2種類の問題を検出・修正する。

1. **統一問題**: カスタムクラスをセマンティッククラスへ書き換えるべき箇所
2. **抽出問題**: 複数ファイルで繰り返されるスタイル群を新規共通クラスとして定義すべき箇所

**前提:** セマンティッククラスとは `resources/css/app.css` に定義された共通クラスを指す（`btn-primary`・`form-control`・`badge-*` 等）。

---

## ステップ 1: app.css の読み込み

`resources/css/app.css` を読み込み、以下を把握する。

- 定義されているセマンティッククラス名の一覧
- 各クラスのプロパティ（後述の重複検出に使う）
- 使用されていそうなカスタムクラスの定義（`search-input`・`search-select` 等）

---

## ステップ 2: Blade ファイルのスキャン

`resources/views/` 以下の全 `.blade.php` を読み込む。
ただし `resources/views/components/` 内はスキャン対象から除外する。

引数でファイルパスが指定された場合はそのファイルのみをスキャンする。

各ファイルから以下を収集する:
- `class="..."` 属性に含まれる全クラス名
- クラスが使われているタグ種別（button・input・select・span・div 等）
- 行番号

---

## ステップ 3: 問題の検出

### 問題 A — カスタムクラスをセマンティッククラスに統一すべき箇所

以下のいずれかに該当するクラスを検出する。

| 検出条件 | 例 | 推奨置き換え先 |
|---------|---|--------------|
| `app.css` にカスタム定義があり、セマンティッククラスと同等のスタイルを持つ | `.search-input` | `form-control` |
| セマンティッククラスと役割が同じだがクラス名が異なる | `.custom-btn-blue` | `btn-primary` |
| 同じタグに対して複数のカスタムクラスが混在している | button に `.my-btn` と `btn` が混在 | `btn-primary` に統一 |

記録する情報: ファイル名・行番号・現在のクラス名・推奨置き換え先・判定根拠。

### 問題 B — 重複スタイルを新規共通クラスとして抽出すべき箇所

以下の手順で検出する。

1. `app.css` 内の全カスタムクラス（セマンティッククラス以外）のプロパティ群を収集する
2. **2ファイル以上**の Blade で同じカスタムクラスが使われているものを「重複候補」とする
3. そのクラスが `app.css` に定義されていない場合は「定義漏れ」として記録する
4. `app.css` に類似プロパティを持つ別名クラスが存在する場合は「類似クラス」として記録する

記録する情報: クラス名・使用ファイル数・使用箇所一覧・app.css の定義有無・類似クラス名。

### 問題 C — 未使用クラス（app.css にあるが Blade で使われていない）

`app.css` に定義されているが、スキャンした Blade ファイルのいずれにも登場しないクラスを検出する。

> 注意: スキャン範囲が一部ファイルに限られる場合（引数指定時）、未使用と断定せず「対象ファイル内では未使用」と記載する。

---

## ステップ 4: 差分レポートの出力

検出結果を以下のフォーマットで出力する。**この時点ではファイルを一切変更しない。**

### セクション A: 統一すべきクラス

```
## A. カスタムクラス → セマンティッククラスへの統一候補

| # | 現在のクラス | 推奨クラス | 出現ファイル | 出現箇所 | 判定根拠 |
|---|------------|----------|------------|---------|---------|
| 1 | search-input | form-control | products/index, customers/index, warehouses/index | input[name=search] × 3 | app.css で同等スタイル定義済み |
| 2 | search-select | form-control | products/index | select[name=category_id] × 1 | app.css で同等スタイル定義済み |
```

### セクション B: 新規共通クラスの抽出候補

```
## B. 新規共通クラスの抽出候補

| # | クラス名 | 使用ファイル数 | 使用箇所 | app.css 定義 | 推奨対応 |
|---|---------|-------------|---------|-------------|---------|
| 3 | custom-table-row | 3ファイル | tr × 8 | なし | app.css に追加 |
| 4 | page-action-btn  | 2ファイル | a × 4  | 類似: btn-primary | btn-primary に統一 |
```

### セクション C: 未使用クラス（削除候補）

```
## C. app.css の未使用クラス（削除候補）

| # | クラス名 | app.css 行 | 備考 |
|---|---------|-----------|------|
| 5 | .old-header-style | 42行 | スキャン対象全ファイルで未使用 |
```

---

## ステップ 5: ユーザーに適用する番号を確認する

AskUserQuestion ツールを使い、以下を質問する。

- **質問**: 「適用する変更の番号を選んでください（複数選択可）」
- **選択肢**: 候補ごとに1つのオプション（「# 番号: 内容の要約」）
- **multiSelect: true**

---

## ステップ 6: 選択された変更を適用する

選択された番号の変更を順番に実施する。

### 6-1. Blade ファイルの書き換え（問題 A・B 対象）

対象ファイルを読み込み、クラス名を置き換える。

**置き換えルール:**
- `class="..."` 内の対象クラス名だけを置き換える（他のクラスは残す）
- `class="{{ ... }}"` などの動的クラスは内容を確認してから置き換える
- インデントや改行は元のコードに合わせる

**置き換え例:**

```blade
{{-- 変換前 --}}
<input type="text" name="search" class="search-input">

{{-- 変換後 --}}
<input type="text" name="search" class="form-control">
```

```blade
{{-- 変換前 --}}
<select name="category_id" class="search-select">

{{-- 変換後 --}}
<select name="category_id" class="form-control">
```

### 6-2. app.css の更新

以下を順番に実施する。

**A. 不要クラスの削除（問題 A で統一されたクラスが app.css にある場合）**
- Blade 側で使われなくなったカスタムクラスの定義を app.css から削除する
- 削除前にそのクラスがスキャン対象外のファイルで使われていないか確認し、懸念がある場合はユーザーに確認する

**B. 新規共通クラスの追加（問題 B で新規定義が必要な場合）**
- 関連する既存クラスの近くに新しいクラスを追加する
- プロパティは Blade での使われ方から推定する
- コメントで追加理由を1行記載する（`/* css-unify: 複数画面共通 */`）

---

## ステップ 7: 完了レポートの出力

```
## 完了レポート

### 書き換えたファイル
- resources/views/products/index.blade.php — search-input → form-control (1箇所), search-select → form-control (1箇所)
- resources/views/customers/index.blade.php — search-input → form-control (1箇所)
- resources/views/warehouses/index.blade.php — search-input → form-control (1箇所)

### app.css の変更
- 削除: .search-input (Blade 側で form-control に統一済み)
- 削除: .search-select (Blade 側で form-control に統一済み)
- 追加: .custom-table-row (複数画面共通クラスとして定義)
```
