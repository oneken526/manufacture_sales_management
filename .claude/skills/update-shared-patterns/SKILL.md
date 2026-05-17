---
name: update-shared-patterns
description: >
  リファクタリング後に共通パターン集（shared-patterns.md・patterns/配下）を更新するスキル。
  「リファクタリングした」「共通メソッドを追加した」「パターンを整理した」「shared-patternsを更新して」
  などのリクエスト、またはリファクタリング作業の完了後に積極的に使用すること。
  tdd-refactor スキル実行後も必ずこのスキルを呼び出すこと。
---

# 共通パターン集 更新スキル

リファクタリングで生まれた共通メソッド・定型パターンを `shared-patterns.md` と `patterns/` 配下のカテゴリファイルに記録する。

---

## 対象ファイル

| ファイル | 役割 |
|---|---|
| `docs/implements/manufacturing-sales-mgmt/shared-patterns.md` | パターン早見表（インデックス） |
| `docs/implements/manufacturing-sales-mgmt/patterns/models.md` | モデルメソッドの詳細 |
| `docs/implements/manufacturing-sales-mgmt/patterns/queries.md` | クエリ・コントローラー・バリデーションの定型パターン |
| `docs/implements/manufacturing-sales-mgmt/patterns/blade-components.md` | Blade コンポーネントの使い方 |
| `docs/implements/manufacturing-sales-mgmt/patterns/routing.md` | named route・URL生成パターン |

---

## 手順

### step1: 更新対象を判定する

今回のリファクタリング・実装で以下のいずれかが生まれたか確認する。

| 生まれたもの | 登録先カテゴリファイル |
|---|---|
| モデルに共通メソッドを追加した（例: `statusBadge()`, `saveDetails()`） | `patterns/models.md` |
| コントローラー・クエリの定型パターンを確立した（例: AND検索・ページネーション） | `patterns/queries.md` |
| バリデーションの定型パターンを確立した（例: `Rule::exists()`） | `patterns/queries.md` |
| Blade コンポーネントの新しい使い方を発見した | `patterns/blade-components.md` |
| named routes など URL 生成の共通パターンを決めた | `patterns/routing.md` |

登録対象がひとつもない場合は「追加すべきパターンはありませんでした」と伝えて終了する。

### step2: 既存内容を読み込む

更新するカテゴリファイルと `shared-patterns.md` を読み込み、重複がないか確認する。
すでに同じパターンが登録されている場合は追記せず「既存パターン ○○ と同一のため追記をスキップしました」と伝える。

### step3: カテゴリファイルに詳細を追記する

対象のカテゴリファイルに以下の形式で追記する。

```markdown
---

## `ClassName::methodName()` または パターン名

**場所**: `app/...` または該当ファイルパス
**追加タスク**: TASK-xxxx（YYYY-MM-DD）

**概要**: 何をする処理か1〜2文で説明。

**使い方**:
\`\`\`php
// コード例
\`\`\`

**ポイント・注意事項**: （省略可）

**今後使う予定**: どの画面・機能で再利用できるか。
```

### step4: shared-patterns.md の早見表に1行追加する

早見表はカテゴリごとに分かれたテーブルになっている。
該当するカテゴリのテーブルに以下の形式で1行追加する。

```markdown
| `パターン名` | 概要（20字以内） | TASK-xxxx |
```

カテゴリと対応テーブルの対応:

| カテゴリ | 早見表のセクション |
|---|---|
| モデルメソッド | `### モデルメソッド` |
| クエリ・コントローラー・バリデーション | `### クエリ・コントローラー・バリデーション` |
| Blade コンポーネント | `### Blade コンポーネント` |
| ルーティング | `### ルーティング` |

該当するカテゴリが存在しない場合は新しいセクションを追加し、カテゴリ一覧テーブルにも追記する。

### step5: 更新内容を報告する

追加・更新したパターンの一覧をユーザーに報告する。

```
## shared-patterns.md 更新完了

追加したパターン:
- `ClassName::methodName()` → patterns/models.md
- `Rule::exists()` パターン → patterns/queries.md

スキップしたパターン（既存と重複）:
- （あれば記載）
```
