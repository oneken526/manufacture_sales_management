# 実装進捗

## 完了タスク

| タスク | 名称 | 完了日 |
|--------|------|--------|
| TASK-0001 | 開発環境セットアップ（Breeze/Spatie/dompdf/jQuery導入） | 2026-04-11 |
| TASK-0002 | 全業務テーブルマイグレーション作成 | 2026-04-11 |
| TASK-0003 | Spatie Permission ロール設定・シーダー | 2026-04-11 |
| TASK-0004 | 共通 Blade レイアウト・ロール別ナビゲーション | 2026-04-12 |
| TASK-0005 | 認証画面カスタマイズ | 2026-04-12 |
| TASK-0006 | 得意先管理 CRUD（CustomerController） | 2026-04-12 |
| TASK-0007 | 商品・カテゴリ管理 CRUD（ProductController） | 2026-04-12 |

## テスト状況

- 全テスト: 17件 / 17件 PASS
  - Feature/Auth/AuthenticationTest: 7件
  - Feature/Auth/PasswordResetTest: 2件
  - Feature/DashboardTest: 8件

## 次のタスク

- TASK-0008: 倉庫管理 CRUD（WarehouseController）

## 主要実装済みファイル

### 認証・レイアウト
- `resources/views/layouts/app.blade.php` — 固定ヘッダー＋サイドバーレイアウト
- `resources/views/layouts/guest.blade.php` — ゲスト向けカードレイアウト
- `resources/views/layouts/partials/sidebar.blade.php` — ロール別サイドバー
- `resources/views/auth/login.blade.php` — 日本語カスタムログイン画面
- `resources/views/auth/forgot-password.blade.php` — 日本語パスワードリセット画面

### コントローラー
- `app/Http/Controllers/DashboardController.php` — ロール別ダッシュボード表示
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` — ロール別リダイレクト・ログアウト
- `app/Http/Controllers/CustomerController.php` — 得意先 CRUD（index/create/store/show/edit/update/destroy）

### モデル
- `app/Models/Customer.php` — SoftDeletes・fillable・リレーション・締日ラベルアクセサ
- `app/Models/CustomerSpecialPrice.php` — SoftDeletes・customer/product リレーション

### フォームリクエスト
- `app/Http/Requests/CustomerRequest.php` — 得意先バリデーション（コードユニーク・締日・与信限度額）
- `app/Http/Controllers/ProductCategoryController.php` — カテゴリ CRUD・子商品ありの削除ガード
- `app/Http/Controllers/ProductController.php` — 商品 CRUD + Ajax 検索（`GET /api/products/search`）
- `app/Models/ProductCategory.php` — SoftDeletes・products リレーション
- `app/Models/Product.php` — SoftDeletes・category/specialPrices/stockLots リレーション
- `app/Http/Requests/ProductCategoryRequest.php` — カテゴリ名ユニークバリデーション
- `app/Http/Requests/ProductRequest.php` — 商品コードユニーク・カテゴリ存在・標準単価バリデーション

### データベース
- 20本のマイグレーション（全業務テーブル）
- `database/seeders/RoleSeeder.php` — 4ロール（admin/sales/manufacture/warehouse）
- `database/seeders/AdminUserSeeder.php` — ロール別初期ユーザー4名
