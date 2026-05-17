# TDD開発メモ: quotation-index

## 概要

- 機能名: 見積書一覧画面
- タスクID: TASK-0014
- 開発開始: 2026-05-17
- 現在のフェーズ: Refactor 完了

## 関連ファイル

- タスクファイル: `docs/tasks/manufacturing-sales-mgmt/TASK-0014.md`
- 仕様書: `docs/spec/manufacturing-sales-mgmt/feature-specs/quotation-management/quotation-list.md`
- テストファイル: `tests/Feature/Quotation/QuotationIndexTest.php`
- コントローラ: `app/Http/Controllers/QuotationController.php`
- モデル: `app/Models/Quotation.php`
- ビュー: `resources/views/quotations/index.blade.php`
- ルート: `routes/web.php`
- ファクトリ: `database/factories/CustomerFactory.php`、`database/factories/QuotationFactory.php`

---

## Red フェーズ（失敗テスト作成）

### 作成日時

2026-05-17（テストファイルは実装前から存在）

### テストケース（全23件）

| # | テストメソッド | 仕様書参照 | 信頼性 |
|---|-------------|----------|--------|
| 1 | 未認証ユーザーはloginにリダイレクトされる | §2 | 🔵 |
| 2 | adminは見積書一覧にアクセスできる | §2 | 🔵 |
| 3 | salesは見積書一覧にアクセスできる | §2 | 🔵 |
| 4 | manufactureは見積書一覧にアクセスできない | §2 | 🔵 |
| 5 | warehouseは見積書一覧にアクセスできない | §2 | 🔵 |
| 6 | 見積書の各項目が一覧に表示される | §3 | 🔵 |
| 7 | 見積書一覧に新規登録ボタンが表示される | §6 | 🔵 |
| 8 | 一覧は1ページあたり20件表示される | §3 | 🔵 |
| 9 | ページネーションの2ページ目に残りの件数が表示される | §3 | 🔵 |
| 10 | 一覧は作成日の降順で表示される | §3 | 🔵 |
| 11 | 削除済みの見積書は一覧に表示されない | §3 | 🔵 |
| 12 | 見積番号で部分一致検索できる | §4 | 🔵 |
| 13 | 得意先名で部分一致検索できる | §4 | 🔵 |
| 14 | ステータスで完全一致検索できる | §4 | 🔵 |
| 15 | 作成日の開始日で絞り込みできる | §4 | 🔵 |
| 16 | 作成日の終了日で絞り込みできる | §4 | 🔵 |
| 17 | 複数条件はANDで絞り込まれる | §4 | 🔵 |
| 18 | draft状態の見積書には編集ボタンが表示される | §6 | 🔵 |
| 19 | pending状態の見積書には編集ボタンが表示されない | §6・§7 | 🔵 |
| 20 | approved状態の見積書には編集ボタンが表示されない | §6 | 🔵 |
| 21 | rejected状態の見積書には編集ボタンが表示されない | §6 | 🔵 |
| 22 | 詳細ボタンはすべてのステータスで表示される | §6 | 🔵 |
| 23 | 複製ボタンはすべてのステータスで表示される | §6 | 🔵 |

### 期待された失敗内容

- `CustomerFactory` が存在せず setUp で例外
- `/quotations` ルートが未登録のため 404
- `QuotationController` が存在しない

---

## Green フェーズ（最小実装）

### 実装日時

2026-05-17

### 実装方針

テストを通すための最小実装を4ファイルで実現。

### 作成ファイル

**1. `database/factories/CustomerFactory.php`**
- テストの `setUp()` で `Customer::factory()` を使うために必要
- `code` は連番（`C0001`, `C0002`, ...）で一意性を保証

**2. `app/Http/Controllers/QuotationController.php`**
- `index` アクションのみ実装
- 5条件の検索（見積番号・得意先名・ステータス・作成日from/to）
- `with(['customer', 'user'])` で N+1 を防止
- `orderByDesc('created_at')->paginate(20)->withQueryString()`

**3. `routes/web.php`**
- `role:admin|sales` グループに `quotations` リソースルートを追加
- `quotations/{quotation}/copy` ルートを追加（複製ボタン用）

**4. `resources/views/quotations/index.blade.php`**
- 検索フォーム・一覧テーブル・ページネーションを実装
- ステータスバッジ・操作ボタンの表示制御

### テスト結果

```
Tests: 23 passed (41 assertions)  ← QuotationIndexTest
Tests: 75 passed (156 assertions) ← 全体
```

---

## Refactor フェーズ（品質改善）

### 実施日時

2026-05-17

### 改善内容

1. **`statusBadge()` をモデルに移動**: ビュー内の `@php` ブロックを除去し、`Quotation` モデルにメソッドとして定義。再利用性・単一責任原則の観点から改善。

2. **ルートURL の統一**: Green フェーズで `duplicate`（ルート）と `copy`（ビュー）が食い違っていた。`copy` に統一し、`route('quotations.copy', $quotation)` で参照。

3. **Named routes 適用**: ビューのハードコードURLを `route()` ヘルパーに置換。詳細・編集リンクは `route(..., false)` で相対URL を生成（テストの `assertSee` が相対URLを期待しているため）。

### テスト結果

```
Tests: 75 passed (156 assertions)
Duration: 3.35s
```

### 品質評価

✅ 高品質 — セキュリティ・パフォーマンスともに問題なし

詳細: `docs/implements/manufacturing-sales-mgmt/TASK-0014/quotation-index-refactor-phase.md`
