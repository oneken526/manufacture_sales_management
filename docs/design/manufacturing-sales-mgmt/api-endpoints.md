# 製造業向け販売管理システム Webルート仕様

**作成日**: 2026-04-11
**関連設計**: [architecture.md](architecture.md)
**関連要件定義**: [requirements.md](../../spec/manufacturing-sales-mgmt/requirements.md)

**注記**: 本システムは Laravel Blade による SSR（サーバーサイドレンダリング）アーキテクチャのため、REST API ではなく Web ルート（`routes/web.php`）仕様として定義します。

**【信頼性レベル凡例】**:
- 🔵 **青信号**: EARS要件定義書・設計文書・ユーザヒアリングを参考にした確実な定義
- 🟡 **黄信号**: EARS要件定義書・設計文書・ユーザヒアリングから妥当な推測による定義
- 🔴 **赤信号**: EARS要件定義書・設計文書・ユーザヒアリングにない推測による定義

---

## 共通仕様

### ミドルウェア 🔵

**信頼性**: 🔵 *REQ-011・REQ-012・tech-stack.md より*

| ミドルウェア | 説明 | 適用範囲 |
|---|---|---|
| `auth` | 未ログイン時にログインページへリダイレクト（REQ-012） | 全保護ルート |
| `role:admin` | admin ロールのみアクセス可（Spatie Permission） | 管理者専用機能 |
| `role:admin\|sales` | admin または sales ロール | 見積・受注・マスタ管理 |
| `role:admin\|manufacture` | admin または manufacture ロール | 製造指示 |
| `role:admin\|warehouse` | admin または warehouse ロール | 出荷・在庫 |

### レスポンス形式 🔵

**信頼性**: 🔵 *tech-stack.md より*

- 通常ページ: Blade テンプレート（HTML レスポンス）
- フォーム送信後: リダイレクト（Post/Redirect/Get パターン）
- Ajax リクエスト: JSON レスポンス（jQuery + `fetch` / `$.ajax()`）
- PDF ダウンロード: バイナリレスポンス（`Content-Type: application/pdf`）

---

## ルート一覧

### 認証（Laravel Breeze）🔵

**信頼性**: 🔵 *REQ-001・REQ-002・Laravel Breeze 標準より*

| メソッド | URI | コントローラー | 説明 |
|---|---|---|---|
| GET | `/login` | `Auth\AuthenticatedSessionController@create` | ログイン画面 |
| POST | `/login` | `Auth\AuthenticatedSessionController@store` | ログイン処理 |
| POST | `/logout` | `Auth\AuthenticatedSessionController@destroy` | ログアウト処理 |
| GET | `/forgot-password` | `Auth\PasswordResetLinkController@create` | パスワードリセット申請画面 |
| POST | `/forgot-password` | `Auth\PasswordResetLinkController@store` | リセットメール送信 |
| GET | `/reset-password/{token}` | `Auth\NewPasswordController@create` | パスワード再設定画面 |
| POST | `/reset-password` | `Auth\NewPasswordController@store` | パスワード再設定処理 |

---

### ダッシュボード 🔵

**信頼性**: 🔵 *REQ-004・US-003 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/dashboard` | `DashboardController@index` | 全ロール | ロール別ダッシュボード（認証済みリダイレクト先） |

---

### マスタ管理

#### 得意先管理 🔵

**信頼性**: 🔵 *REQ-005・REQ-006・US-005・US-006 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/customers` | `CustomerController@index` | admin, sales | 得意先一覧 |
| GET | `/customers/create` | `CustomerController@create` | admin, sales | 得意先作成画面 |
| POST | `/customers` | `CustomerController@store` | admin, sales | 得意先保存 |
| GET | `/customers/{id}` | `CustomerController@show` | admin, sales | 得意先詳細 |
| GET | `/customers/{id}/edit` | `CustomerController@edit` | admin, sales | 得意先編集画面 |
| PUT | `/customers/{id}` | `CustomerController@update` | admin, sales | 得意先更新 |
| DELETE | `/customers/{id}` | `CustomerController@destroy` | admin | 得意先削除（ソフトデリート） |
| GET | `/customers/{id}/special-prices` | `CustomerController@specialPrices` | admin, sales | 特別単価一覧 |
| POST | `/customers/{id}/special-prices` | `CustomerController@storeSpecialPrice` | admin, sales | 特別単価保存 |
| DELETE | `/customers/{id}/special-prices/{pid}` | `CustomerController@destroySpecialPrice` | admin | 特別単価削除 |

**Ajax エンドポイント**:

| メソッド | URI | 説明 |
|---|---|---|
| GET | `/api/customers/{id}/special-prices/{product_id}` | 商品の特別単価取得（見積作成時の自動反映: REQ-020） |

#### 商品管理 🔵

**信頼性**: 🔵 *REQ-007・REQ-008・US-007 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/products` | `ProductController@index` | admin | 商品一覧 |
| GET | `/products/create` | `ProductController@create` | admin | 商品作成画面 |
| POST | `/products` | `ProductController@store` | admin | 商品保存 |
| GET | `/products/{id}/edit` | `ProductController@edit` | admin | 商品編集画面 |
| PUT | `/products/{id}` | `ProductController@update` | admin | 商品更新 |
| DELETE | `/products/{id}` | `ProductController@destroy` | admin | 商品削除（ソフトデリート） |
| GET | `/product-categories` | `ProductCategoryController@index` | admin | カテゴリ一覧 |
| POST | `/product-categories` | `ProductCategoryController@store` | admin | カテゴリ保存 |
| PUT | `/product-categories/{id}` | `ProductCategoryController@update` | admin | カテゴリ更新 |
| DELETE | `/product-categories/{id}` | `ProductCategoryController@destroy` | admin | カテゴリ削除 |

#### 倉庫管理 🔵

**信頼性**: 🔵 *REQ-009・US-008 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/warehouses` | `WarehouseController@index` | admin | 倉庫一覧 |
| GET | `/warehouses/create` | `WarehouseController@create` | admin | 倉庫作成画面 |
| POST | `/warehouses` | `WarehouseController@store` | admin | 倉庫保存 |
| GET | `/warehouses/{id}/edit` | `WarehouseController@edit` | admin | 倉庫編集画面 |
| PUT | `/warehouses/{id}` | `WarehouseController@update` | admin | 倉庫更新 |
| DELETE | `/warehouses/{id}` | `WarehouseController@destroy` | admin | 倉庫削除 |

#### ユーザー管理 🔵

**信頼性**: 🔵 *REQ-010・US-009 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/users` | `UserController@index` | admin | ユーザー一覧 |
| GET | `/users/create` | `UserController@create` | admin | ユーザー作成画面 |
| POST | `/users` | `UserController@store` | admin | ユーザー保存 |
| GET | `/users/{id}/edit` | `UserController@edit` | admin | ユーザー編集（ロール変更） |
| PUT | `/users/{id}` | `UserController@update` | admin | ユーザー更新 |
| DELETE | `/users/{id}` | `UserController@destroy` | admin | ユーザー無効化（ソフトデリート） |

---

### 見積管理 🔵

**信頼性**: 🔵 *REQ-013〜REQ-026・US-010〜US-015・ヒアリングQ1・Q7 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/quotations` | `QuotationController@index` | admin, sales | 見積一覧 |
| GET | `/quotations/create` | `QuotationController@create` | sales | 見積作成画面 |
| POST | `/quotations` | `QuotationController@store` | sales | 見積保存（status: draft） |
| GET | `/quotations/{id}` | `QuotationController@show` | admin, sales | 見積詳細 |
| GET | `/quotations/{id}/edit` | `QuotationController@edit` | sales | 見積編集（draft のみ: REQ-026） |
| PUT | `/quotations/{id}` | `QuotationController@update` | sales | 見積更新 |
| DELETE | `/quotations/{id}` | `QuotationController@destroy` | admin, sales | 見積削除（ソフトデリート） |
| POST | `/quotations/{id}/duplicate` | `QuotationController@duplicate` | sales | 見積複製（新 draft 作成: REQ-013） |
| POST | `/quotations/{id}/submit` | `QuotationController@submit` | sales | 承認申請（draft → pending: REQ-015） |
| POST | `/quotations/{id}/approve` | `QuotationController@approve` | admin | 承認（pending → approved: REQ-015） |
| POST | `/quotations/{id}/reject` | `QuotationController@reject` | admin | 却下（pending → rejected: REQ-015） |
| POST | `/quotations/{id}/convert` | `QuotationController@convertToOrder` | sales | 受注変換（approved のみ: REQ-018） |
| GET | `/quotations/{id}/pdf` | `QuotationController@pdf` | admin, sales | 見積書 PDF 出力（REQ-046） |

---

### 受注管理 🔵

**信頼性**: 🔵 *REQ-016〜REQ-026・US-016〜US-020・ヒアリングQ1・Q3・Q7・Q8 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/orders` | `OrderController@index` | admin, sales | 受注一覧（受注残表示: REQ-019） |
| GET | `/orders/create` | `OrderController@create` | sales | 受注作成画面 |
| POST | `/orders` | `OrderController@store` | sales | 受注保存（与信チェック: REQ-024） |
| GET | `/orders/{id}` | `OrderController@show` | admin, sales | 受注詳細 |
| GET | `/orders/{id}/edit` | `OrderController@edit` | sales | 受注編集（draft のみ） |
| PUT | `/orders/{id}` | `OrderController@update` | sales | 受注更新 |
| DELETE | `/orders/{id}` | `OrderController@destroy` | admin | 受注削除（ソフトデリート） |
| POST | `/orders/{id}/submit` | `OrderController@submit` | sales | 承認申請（draft → pending） |
| POST | `/orders/{id}/approve` | `OrderController@approve` | admin | 承認 + 製造指示自動発行（REQ-023） |
| POST | `/orders/{id}/reject` | `OrderController@reject` | admin | 却下 |

---

### 製造指示管理 🔵

**信頼性**: 🔵 *REQ-027〜REQ-029・REQ-035・REQ-039・US-021〜US-024・ヒアリングQ3 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/manufacture-orders` | `ManufactureOrderController@index` | admin, manufacture | 製造指示一覧 |
| GET | `/manufacture-orders/{id}` | `ManufactureOrderController@show` | admin, manufacture | 製造指示詳細（製番トレース: US-024） |
| POST | `/manufacture-orders/{id}/start` | `ManufactureOrderController@start` | manufacture | 着手（pending → in_progress） |
| POST | `/manufacture-orders/{id}/complete` | `ManufactureOrderController@complete` | manufacture | 完了 + 在庫自動入庫（REQ-035・EDGE-010） |
| POST | `/manufacture-orders/{id}/cancel` | `ManufactureOrderController@cancel` | admin | キャンセル（adminのみ: US-022） |

---

### 在庫管理 🔵

**信頼性**: 🔵 *REQ-029〜REQ-032・US-025〜US-027 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/stocks` | `StockController@index` | admin, warehouse | 在庫一覧（ロット別・倉庫別） |
| GET | `/stocks/{lot_id}` | `StockController@show` | admin, warehouse | ロット詳細・移動履歴 |
| POST | `/stocks/transfer` | `StockController@transfer` | warehouse | 倉庫間移動（REQ-031） |
| POST | `/stocks/adjust` | `StockController@adjust` | admin, warehouse | 棚卸・在庫調整（REQ-032） |
| GET | `/stocks/movements` | `StockController@movements` | admin, warehouse | 在庫移動履歴一覧 |

---

### 出荷管理 🔵

**信頼性**: 🔵 *REQ-033〜REQ-034・REQ-036〜REQ-038・US-028〜US-030 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/shipments` | `ShipmentController@index` | admin, warehouse, sales | 出荷一覧 |
| GET | `/shipments/create` | `ShipmentController@create` | warehouse | 出荷登録画面（受注指定） |
| POST | `/shipments` | `ShipmentController@store` | warehouse | 出荷登録確定（在庫引当: REQ-036） |
| GET | `/shipments/{id}` | `ShipmentController@show` | admin, warehouse, sales | 出荷詳細 |
| POST | `/shipments/{id}/return` | `ShipmentController@return` | admin, warehouse | 返品処理（REQ-034・REQ-037） |
| GET | `/shipments/{id}/pdf` | `ShipmentController@pdf` | admin, warehouse, sales | 納品書 PDF 出力（REQ-047） |

---

### 請求管理 🔵

**信頼性**: 🔵 *REQ-040〜REQ-045・US-031〜US-033・ヒアリングQ5 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/invoices` | `InvoiceController@index` | admin, sales | 請求書一覧 |
| GET | `/invoices/generate` | `InvoiceController@generateForm` | admin | 月次請求書生成画面（手動操作: REQ-045） |
| POST | `/invoices/generate` | `InvoiceController@generate` | admin | 月次請求書生成実行（REQ-044・EDGE-004） |
| GET | `/invoices/{id}` | `InvoiceController@show` | admin, sales | 請求書詳細・売掛金元帳（REQ-041） |
| DELETE | `/invoices/{id}` | `InvoiceController@destroy` | admin | 請求書削除（ソフトデリート） |
| GET | `/invoices/{id}/pdf` | `InvoiceController@pdf` | admin, sales | 請求書 PDF 出力（REQ-048） |

---

### 入金管理 🔵

**信頼性**: 🔵 *REQ-042〜REQ-043・US-034〜US-036・ヒアリングQ6 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/payments` | `PaymentController@index` | admin | 入金一覧 |
| GET | `/payments/create` | `PaymentController@create` | admin | 入金登録画面 |
| POST | `/payments` | `PaymentController@store` | admin | 入金登録（EDGE-009: 金額1以上） |
| GET | `/payments/{id}` | `PaymentController@show` | admin | 入金詳細 |
| GET | `/payments/{id}/allocate` | `PaymentController@allocateForm` | admin | 消込画面（複数請求書選択） |
| POST | `/payments/{id}/allocate` | `PaymentController@allocate` | admin | 消込処理（REQ-043・EDGE-005） |
| DELETE | `/payments/{id}/allocate/{aid}` | `PaymentController@deallocate` | admin | 消込取消（US-036） |

---

### レポート 🔵

**信頼性**: 🔵 *REQ-049〜REQ-051・US-037〜US-041・ヒアリングQ9 より*

| メソッド | URI | コントローラー | ロール | 説明 |
|---|---|---|---|---|
| GET | `/reports/sales` | `ReportController@sales` | admin, sales | 売上集計レポート（月次・得意先別・商品別: REQ-049） |
| GET | `/reports/stock` | `ReportController@stock` | admin, warehouse | 在庫推移レポート（REQ-050） |
| GET | `/reports/backorder` | `ReportController@backorder` | admin, sales | 受注残レポート（REQ-051） |
| GET | `/reports/sales/export` | `ReportController@exportSales` | admin | 売上レポート CSV エクスポート（US-041） |
| GET | `/reports/stock/export` | `ReportController@exportStock` | admin | 在庫レポート CSV エクスポート |
| GET | `/reports/backorder/export` | `ReportController@exportBackorder` | admin | 受注残レポート CSV エクスポート |

---

## バリデーションエラーレスポンス 🔵

**信頼性**: 🔵 *Laravel 標準・REQ-059 より*

フォーム送信時のバリデーションエラーは Laravel の FormRequest で処理し、同じフォーム画面にリダイレクトします。

```blade
{{-- エラーメッセージ表示（全フォーム共通） --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

---

## Ajax エンドポイント一覧 🟡

**信頼性**: 🟡 *フォームの動的操作要件から妥当な推測*

以下は jQuery Ajax による動的操作に使用するエンドポイントです。

| メソッド | URI | 説明 |
|---|---|---|
| GET | `/api/customers/{id}/special-price/{product_id}` | 特別単価取得（REQ-020） |
| GET | `/api/stocks/lots?product_id={id}&warehouse_id={id}` | 出荷可能ロット一覧取得（REQ-033） |
| GET | `/api/orders/{id}/credit-check` | 与信チェック実行（REQ-024） |
| GET | `/api/invoices?customer_id={id}&unpaid=1` | 未払い請求書一覧取得（消込画面用） |

---

## 関連文書

- **アーキテクチャ**: [architecture.md](architecture.md)
- **データフロー**: [dataflow.md](dataflow.md)
- **DBスキーマ**: [database-schema.sql](database-schema.sql)
- **要件定義**: [requirements.md](../../spec/manufacturing-sales-mgmt/requirements.md)

---

## 信頼性レベルサマリー

- 🔵 青信号: 15件 (94%)
- 🟡 黄信号: 1件 (6%)  ← Ajax エンドポイント詳細
- 🔴 赤信号: 0件 (0%)

**品質評価**: ✅ 高品質
