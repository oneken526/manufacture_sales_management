# 見積書一覧画面 実装〜テストの流れ

作成日: 2026-05-17  
対応仕様書: [quotation-list.md](./quotation-list.md)  
自動テスト: [QuotationIndexTest.php](../../../../tests/Feature/Quotation/QuotationIndexTest.php)  
手動確認: [quotation-list-checklist.md](./quotation-list-checklist.md)

---

## 全体像

```
仕様書 → Red（テスト失敗確認）→ 実装 → Green（テスト通過）→ 手動確認
```

---

## Step 1: テストが失敗することを確認（Red）

まず、自動テストが「正しく失敗している」ことを確認します。  
モデルもルートも存在しないので、全件失敗するはずです。

```bash
php artisan test tests/Feature/Quotation/QuotationIndexTest.php
```

**テストが最初から通っている場合は、テストが正しく書かれていない可能性があります。**

---

## Step 2: 実装

テストを通すために必要な実装を以下の順番で行います。

### 2-1. マイグレーション

`database/migrations/` に以下のテーブルを作成します。

- `quotations` テーブル
- `quotation_details` テーブル

> スキーマの詳細は [database-schema.sql](../../../design/manufacturing-sales-mgmt/database-schema.sql) を参照してください。  
> **`php artisan migrate` は実行せず、作成後にユーザーへ確認を取ること。**

### 2-2. モデル・ファクトリ

```
app/Models/Quotation.php
database/factories/QuotationFactory.php
```

Quotation モデルには以下のリレーションが必要です。

| リレーション | 内容 |
|---|---|
| `customer()` | belongsTo Customer |
| `user()` | belongsTo User（作成者） |
| `details()` | hasMany QuotationDetail |

### 2-3. ルート

`routes/web.php` に以下を追加します。

```php
Route::resource('quotations', QuotationController::class);
```

ミドルウェアで `admin` と `sales` のみアクセス可能に制限してください。

### 2-4. コントローラー

```
app/Http/Controllers/QuotationController.php
```

`index` メソッドで実装すべき内容：

| 仕様 | 実装内容 |
|---|---|
| デフォルト降順 | `orderBy('created_at', 'desc')` |
| 20件ページネーション | `paginate(20)` |
| 論理削除除外 | Eloquent の SoftDeletes が自動対応 |
| 見積番号検索（部分一致） | `when($request->quotation_number, fn($q, $v) => $q->where('quotation_number', 'like', "%{$v}%"))` |
| 得意先名検索（部分一致） | customers テーブルを JOIN して `where('customers.name', 'like', ...)` |
| ステータス検索（完全一致） | `when($request->status, fn($q, $v) => $q->where('status', $v))` |
| 作成日（開始） | `when($request->created_from, fn($q, $v) => $q->whereDate('created_at', '>=', $v))` |
| 作成日（終了） | `when($request->created_to, fn($q, $v) => $q->whereDate('created_at', '<=', $v))` |

### 2-5. ビュー

```
resources/views/quotations/index.blade.php
```

ビューで実装すべき内容：

| 仕様 | 実装内容 |
|---|---|
| 表示カラム | 見積番号・得意先名・合計金額・有効期限・ステータス・作成者・作成日・操作 |
| ステータスバッジ | draft=グレー / pending=黄 / approved=緑 / rejected=赤 |
| 有効期限切れの強調 | `valid_until < today` の場合に赤字等で強調表示 |
| 編集ボタンの表示制御 | `status === 'draft'` のときのみ表示 |
| 詳細・複製ボタン | 全ステータスで表示 |
| 新規登録ボタン | 画面右上に常時表示 |
| 検索フォーム | 5項目の入力欄と「検索」「クリア」ボタン |
| ページネーション | `{{ $quotations->links() }}` |

---

## Step 3: テストが通ることを確認（Green）

実装が終わったら再度テストを実行します。

```bash
php artisan test tests/Feature/Quotation/QuotationIndexTest.php
```

全件 ✅ になれば、仕様書に定義したロジック・権限・検索・ボタン制御が正しく実装されていることが証明されます。

---

## Step 4: 既存テストへの影響確認

新しい実装が既存機能を壊していないか確認します。

```bash
php artisan test
```

---

## Step 5: 手動確認チェックリスト（見た目の確認）

自動テストでは確認できない「バッジの色」「有効期限の強調表示」などをブラウザで確認します。

```bash
php artisan serve
```

[quotation-list-checklist.md](./quotation-list-checklist.md) を開きながら、全項目を確認して記録してください。

---

## 各ステップの担当分担

| ステップ | 作業者 | ツール |
|---|---|---|
| Step 1: Red 確認 | 実装者 | `php artisan test` |
| Step 2: 実装 | 実装者 | エディタ・Laravel |
| Step 3: Green 確認 | 実装者 | `php artisan test` |
| Step 4: 既存テスト確認 | 実装者 | `php artisan test` |
| Step 5: 手動確認 | レビュアー | ブラウザ + チェックリスト |
