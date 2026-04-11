# 製造業向け販売管理システム アーキテクチャ設計

**作成日**: 2026-04-11
**関連要件定義**: [requirements.md](../../spec/manufacturing-sales-mgmt/requirements.md)
**ヒアリング記録**: [design-interview.md](design-interview.md)

**【信頼性レベル凡例】**:
- 🔵 **青信号**: EARS要件定義書・設計文書・ユーザヒアリングを参考にした確実な設計
- 🟡 **黄信号**: EARS要件定義書・設計文書・ユーザヒアリングから妥当な推測による設計
- 🔴 **赤信号**: EARS要件定義書・設計文書・ユーザヒアリングにない推測による設計

---

## システム概要 🔵

**信頼性**: 🔵 *要件定義書概要・note.mdより*

製造業向けの販売管理システム。見積・受注・製造指示・出荷・在庫・請求・入金の一連の業務フローを一元管理する。
4つのロール（admin / sales / manufacture / warehouse）がそれぞれの業務を担当し、承認ワークフローで品質を担保する。

**対象ユーザー規模**: 同時利用者10人以下（軽負荷環境）

---

## アーキテクチャパターン 🔵

**信頼性**: 🔵 *tech-stack.md・既存プロジェクト構造より*

- **パターン**: レイヤードアーキテクチャ（Layered Architecture）
- **レイヤー構成**:
  - **プレゼンテーション層**: Blade テンプレート + Tailwind CSS + jQuery
  - **アプリケーション層**: HTTP Controllers + FormRequest
  - **ドメイン層**: Services（ビジネスロジック）
  - **インフラ層**: Eloquent Models + MySQL
- **選択理由**: Laravel の標準的なアーキテクチャで学習コストが低く、CRUD 中心の業務システムに最適。SPA フレームワーク不使用でシンプルな実装を実現。

---

## コンポーネント構成

### フロントエンド 🔵

**信頼性**: 🔵 *tech-stack.md より*

| 項目 | 技術 | バージョン |
|---|---|---|
| テンプレートエンジン | Laravel Blade | Laravel 13 組み込み |
| CSS フレームワーク | Tailwind CSS | 4.0 |
| JavaScript | jQuery | 3.x |
| バンドラー | Vite | 6.x |
| レンダリング方式 | SSR（サーバーサイドレンダリング） | - |

**注記**: SPA フレームワーク（React / Vue.js）は使用しない。動的フォーム操作（明細行追加・単価自動反映）は jQuery で実装。

### バックエンド 🔵

**信頼性**: 🔵 *tech-stack.md より*

| 項目 | 技術 | バージョン |
|---|---|---|
| フレームワーク | Laravel | 13.0 |
| 言語 | PHP | 8.3+ |
| ORM | Eloquent | Laravel 13 組み込み |
| 認証 | Laravel Breeze（Blade スタック） | 最新 |
| 権限管理 | Spatie Laravel Permission | 6.x |
| PDF 出力 | barryvdh/laravel-dompdf | 最新安定版 |
| キュー | database ドライバ | - |
| メール | 同期送信（Laravel Mail） | - |

**メール実装方針** 🔵: ヒアリング確認により同期送信を採用。送信失敗時は承認申請処理ごとロールバック（EDGE-001 対応済み）。

### データベース 🔵

**信頼性**: 🔵 *tech-stack.md・要件定義より*

| 項目 | 技術 | バージョン |
|---|---|---|
| DBMS | MySQL | 8.0+ |
| キャッシュ | database ドライバ（初期） | - |
| ORM | Eloquent ORM | - |
| ソフトデリート | 全業務テーブルに `deleted_at` 適用 | - |

---

## システム構成図

**信頼性**: 🔵 *要件定義・tech-stack.md より*

```mermaid
graph TB
    subgraph ブラウザ
        U[ユーザー<br/>admin/sales/manufacture/warehouse]
    end

    subgraph Webサーバー（VPS: Nginx + PHP-FPM）
        subgraph Laravel Application
            B[Blade テンプレート<br/>Tailwind CSS / jQuery]
            C[HTTP Controllers<br/>+ FormRequest]
            S[Services<br/>ビジネスロジック]
            M[Eloquent Models<br/>SoftDelete]
        end
    end

    subgraph データ層
        DB[(MySQL 8.0+)]
        FS[ローカルディスク<br/>PDF保存]
    end

    subgraph 外部サービス
        MAIL[メールサーバー<br/>SMTP]
    end

    U -->|HTTPS| B
    B --> C
    C --> S
    S --> M
    M --> DB
    C --> FS
    S -->|同期送信| MAIL
```

---

## ロール・権限設計

**信頼性**: 🔵 *REQ-003・REQ-011・ヒアリングQ10 より*

### ロール定義

| ロール | 役割 | 主要アクセス範囲 |
|---|---|---|
| `admin` | システム管理者 | 全機能（承認・月次請求書生成・マスタ管理） |
| `sales` | 営業担当 | 見積・受注・得意先・PDF出力・売上レポート |
| `manufacture` | 製造担当 | 製造指示・進捗更新・在庫入庫 |
| `warehouse` | 倉庫担当 | 出荷・在庫移動・棚卸・納品書PDF |

### 承認ワークフロー

**信頼性**: 🔵 *REQ-025・ヒアリングQ1 より*

```
draft（申請者が編集） → pending（申請済み・編集不可） → approved または rejected
                                ↑ メール: admin へ通知        ↑ メール: 申請者へ通知
```

- **一段階承認**: admin ロールを持つ管理者一人が承認・却下
- **対象**: 見積書・受注（同じワークフロー）
- **制約**: `pending` 状態は申請者本人も編集不可（REQ-026）

---

## ディレクトリ構造

**信頼性**: 🔵 *tech-stack.md・既存プロジェクト構造より*

```
manufacture_sales_management/（プロジェクトルート）
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                        # Breeze 認証コントローラー
│   │   │   ├── CustomerController.php        # 得意先管理
│   │   │   ├── ProductController.php         # 商品管理
│   │   │   ├── ProductCategoryController.php # 商品カテゴリ管理
│   │   │   ├── WarehouseController.php       # 倉庫管理
│   │   │   ├── QuotationController.php       # 見積管理
│   │   │   ├── OrderController.php           # 受注管理
│   │   │   ├── ManufactureOrderController.php # 製造指示管理
│   │   │   ├── StockController.php           # 在庫管理
│   │   │   ├── ShipmentController.php        # 出荷管理
│   │   │   ├── InvoiceController.php         # 請求管理
│   │   │   ├── PaymentController.php         # 入金管理
│   │   │   ├── DashboardController.php       # ダッシュボード
│   │   │   └── ReportController.php          # レポート
│   │   ├── Requests/
│   │   │   ├── StoreCustomerRequest.php
│   │   │   ├── StoreQuotationRequest.php
│   │   │   ├── StoreOrderRequest.php
│   │   │   ├── StoreShipmentRequest.php
│   │   │   ├── StoreInvoiceRequest.php
│   │   │   ├── StorePaymentRequest.php
│   │   │   └── ...（各業務エンティティ）
│   │   └── Middleware/
│   │       └── （Breeze・Spatie 標準 Middleware 使用）
│   ├── Models/
│   │   ├── User.php
│   │   ├── Customer.php
│   │   ├── CustomerSpecialPrice.php
│   │   ├── ProductCategory.php
│   │   ├── Product.php
│   │   ├── Warehouse.php
│   │   ├── Quotation.php
│   │   ├── QuotationDetail.php
│   │   ├── Order.php
│   │   ├── OrderDetail.php
│   │   ├── ManufactureOrder.php
│   │   ├── StockLot.php
│   │   ├── StockQuantity.php
│   │   ├── StockMovement.php
│   │   ├── Shipment.php
│   │   ├── ShipmentDetail.php
│   │   ├── Invoice.php
│   │   ├── InvoiceDetail.php
│   │   ├── Payment.php
│   │   └── PaymentAllocation.php
│   ├── Services/
│   │   ├── QuotationService.php     # 見積ビジネスロジック・承認ワークフロー
│   │   ├── OrderService.php         # 受注ビジネスロジック・承認ワークフロー・与信チェック
│   │   ├── ManufactureOrderService.php # 製造指示・製番採番
│   │   ├── StockService.php         # 在庫入庫・出庫・移動・調整
│   │   ├── ShipmentService.php      # 出荷登録・ロット引当
│   │   ├── InvoiceService.php       # 月次請求書生成・消込
│   │   ├── PaymentService.php       # 入金登録・充当
│   │   ├── PdfService.php           # PDF 出力（dompdf ラッパー）
│   │   └── NumberingService.php     # 採番サービス（採番形式: 年月+連番）
│   └── Mail/
│       ├── ApprovalRequestMail.php  # 承認申請通知メール
│       └── ApprovalResultMail.php   # 承認結果通知メール
├── database/
│   ├── migrations/
│   │   ├── （Laravel デフォルト）
│   │   ├── xxxx_create_customers_table.php
│   │   ├── xxxx_create_customer_special_prices_table.php
│   │   ├── xxxx_create_product_categories_table.php
│   │   ├── xxxx_create_products_table.php
│   │   ├── xxxx_create_warehouses_table.php
│   │   ├── xxxx_create_quotations_table.php
│   │   ├── xxxx_create_quotation_details_table.php
│   │   ├── xxxx_create_orders_table.php
│   │   ├── xxxx_create_order_details_table.php
│   │   ├── xxxx_create_manufacture_orders_table.php
│   │   ├── xxxx_create_stock_lots_table.php
│   │   ├── xxxx_create_stock_quantities_table.php
│   │   ├── xxxx_create_stock_movements_table.php
│   │   ├── xxxx_create_shipments_table.php
│   │   ├── xxxx_create_shipment_details_table.php
│   │   ├── xxxx_create_invoices_table.php
│   │   ├── xxxx_create_invoice_details_table.php
│   │   ├── xxxx_create_payments_table.php
│   │   └── xxxx_create_payment_allocations_table.php
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── RoleSeeder.php           # 4ロールの初期投入
│   │   └── AdminUserSeeder.php      # 初期 admin ユーザー
│   └── factories/
│       └── ...（テスト用ファクトリ）
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php        # 認証済みレイアウト
│   │   │   └── guest.blade.php      # ゲストレイアウト
│   │   ├── components/              # 再利用コンポーネント
│   │   ├── auth/                    # Breeze 認証ビュー
│   │   ├── dashboard/               # ロール別ダッシュボード
│   │   ├── customers/               # 得意先管理
│   │   ├── products/                # 商品管理
│   │   ├── warehouses/              # 倉庫管理
│   │   ├── quotations/              # 見積管理
│   │   ├── orders/                  # 受注管理
│   │   ├── manufacture_orders/      # 製造指示管理
│   │   ├── stocks/                  # 在庫管理
│   │   ├── shipments/               # 出荷管理
│   │   ├── invoices/                # 請求管理
│   │   ├── payments/                # 入金管理
│   │   ├── reports/                 # レポート
│   │   └── pdf/                     # PDF 用 Blade テンプレート
│   ├── css/
│   │   └── app.css                  # Tailwind CSS エントリポイント
│   └── js/
│       └── app.js                   # jQuery + カスタム JS エントリポイント
├── routes/
│   └── web.php                      # 全ルーティング定義
├── storage/
│   └── app/public/                  # PDF ファイル保存先
└── tests/
    ├── Feature/                     # フィーチャーテスト
    └── Unit/                        # ユニットテスト
```

---

## 採番設計

**信頼性**: 🔵 *ヒアリング（採番形式: 年月+連番）より*

| ドキュメント | 形式 | 例 |
|---|---|---|
| 見積書番号 | `Q{YYYYMM}-{3桁連番}` | Q202604-001 |
| 受注番号 | `O{YYYYMM}-{3桁連番}` | O202604-001 |
| 製造番号（製番） | `MO{YYYYMM}-{3桁連番}` | MO202604-001 |
| 請求書番号 | `INV{YYYYMM}-{3桁連番}` | INV202604-001 |

**採番ロジック**: `NumberingService` が担当。同一年月の最大連番+1。DB レコード作成時にロック（`lockForUpdate`）して競合を防ぐ。

---

## 非機能要件の実現方法

### パフォーマンス 🔵

**信頼性**: 🔵 *REQ-056・REQ-057・tech-stack.md より*

- **レスポンスタイム目標**: 3秒以内（REQ-056）
- **PDF 出力目標**: 10秒以内（REQ-057）
- **最適化戦略**:
  - Eloquent の Eager Loading（`with()`）で N+1 問題を防止
  - 一覧画面にページネーション（デフォルト 20件/ページ）を適用
  - 頻繁に参照する外部キーカラムにインデックスを設定
  - レポート集計クエリは MySQL の集計関数・グループ化を活用

### セキュリティ 🔵

**信頼性**: 🔵 *REQ-058・REQ-059・tech-stack.md より*

| 対策 | 実装方法 |
|---|---|
| 認証 | Laravel Breeze（セッション認証） |
| 認可 | Spatie Permission（ロールベースアクセス制御）|
| CSRF 保護 | Laravel 標準 CSRF トークン（`@csrf`） |
| XSS 対策 | Blade の自動エスケープ（`{{ }}`） |
| SQLインジェクション対策 | Eloquent ORM パラメータバインディング |
| バリデーション | FormRequest によるサーバーサイドバリデーション |
| ソフトデリート | 全業務テーブルに `deleted_at`（REQ-062） |

### スケーラビリティ 🟡

**信頼性**: 🟡 *tech-stack.md・NFR から妥当な推測*

- **現フェーズ**: 同時利用者10人以下の軽負荷環境
- **スケール時**: Redis キャッシュ・キューへの移行（database ドライバから Redis へ）

### 可用性 🟡

**信頼性**: 🟡 *tech-stack.md から妥当な推測*

- **デプロイ**: VPS（Nginx + PHP-FPM）
- **SSL**: Let's Encrypt（自動更新）

---

## 技術的制約

### パフォーマンス制約 🔵

**信頼性**: 🔵 *tech-stack.md・要件定義より*

- ページレスポンス 3秒以内（REQ-056）
- PDF 出力 10秒以内（REQ-057）

### セキュリティ制約 🔵

**信頼性**: 🔵 *REQ-058・REQ-059 より*

- 認証・認可は必ず Laravel Breeze + Spatie Permission を使用
- CSRF・XSS・SQLi 対策は Laravel 標準機能のみ使用

### 互換性制約 🔵

**信頼性**: 🔵 *tech-stack.md より*

- PHP 8.3+ 必須
- MySQL 8.0+ 必須
- 多言語対応不要（日本語のみ）（REQ-052）
- SPA フレームワーク不使用（Blade + jQuery のみ）

---

## 関連文書

- **データフロー**: [dataflow.md](dataflow.md)
- **DBスキーマ**: [database-schema.sql](database-schema.sql)
- **Webルート仕様**: [api-endpoints.md](api-endpoints.md)
- **ヒアリング記録**: [design-interview.md](design-interview.md)
- **要件定義**: [requirements.md](../../spec/manufacturing-sales-mgmt/requirements.md)
- **ユーザーストーリー**: [user-stories.md](../../spec/manufacturing-sales-mgmt/user-stories.md)

---

## 信頼性レベルサマリー

- 🔵 青信号: 18件 (86%)
- 🟡 黄信号: 3件 (14%)
- 🔴 赤信号: 0件 (0%)

**品質評価**: ✅ 高品質
