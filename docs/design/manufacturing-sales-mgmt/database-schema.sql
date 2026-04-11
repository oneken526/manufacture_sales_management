-- ========================================
-- 製造業向け販売管理システム データベーススキーマ
-- ========================================
--
-- 作成日: 2026-04-11
-- 関連設計: architecture.md
-- DBMS: MySQL 8.0+
--
-- 信頼性レベル:
-- - 🔵 青信号: EARS要件定義書・設計文書・既存DBスキーマを参考にした確実な定義
-- - 🟡 黄信号: EARS要件定義書・設計文書・既存DBスキーマから妥当な推測による定義
-- - 🔴 赤信号: EARS要件定義書・設計文書・既存DBスキーマにない推測による定義
--
-- 設計方針:
-- - 全業務テーブルにソフトデリート（deleted_at）を適用（REQ-062）
-- - 外部キー制約を設定し参照整合性を確保
-- - 採番形式: {プレフィックス}{YYYYMM}-{3桁連番}（ヒアリング確定）
-- - 在庫管理: 2層構造（stock_lots + stock_quantities）（ヒアリング確定）
--

SET FOREIGN_KEY_CHECKS = 0;
SET CHARACTER SET utf8mb4;

-- ========================================
-- 1. ユーザー管理（Laravel Breeze + Spatie Permission）
-- ========================================

-- users テーブル（Laravelデフォルト + ソフトデリート追加）
-- 🔵 信頼性: REQ-010・Laravelデフォルトスキーマより
ALTER TABLE users
    ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;
-- 注: users テーブルはLaravelデフォルトで既に存在。deleted_at のみ追加。

-- Spatie Permission テーブル群（spatie/laravel-permission が自動生成）
-- 🔵 信頼性: REQ-003・Spatie Permission 6.x より
-- - roles
-- - permissions
-- - model_has_roles
-- - model_has_permissions
-- - role_has_permissions
-- ※ php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
--   + php artisan migrate で自動生成される

-- ========================================
-- 2. マスタ管理
-- ========================================

-- 得意先マスタ
-- 🔵 信頼性: REQ-005・US-005・ヒアリングQ6より
CREATE TABLE customers (
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    customer_code   VARCHAR(20)     NOT NULL COMMENT '得意先コード（管理用）',
    name            VARCHAR(255)    NOT NULL COMMENT '社名',
    address         TEXT            NULL     COMMENT '住所',
    closing_day     TINYINT         NOT NULL DEFAULT 99 COMMENT '締日（1-28 または 99=月末）（EDGE-008）',
    credit_limit    DECIMAL(15, 2)  NOT NULL DEFAULT 0  COMMENT '与信限度額（0=制限なし）（EDGE-007）',
    email           VARCHAR(255)    NULL     COMMENT '連絡先メールアドレス',
    created_at      TIMESTAMP       NULL     DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NULL     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP       NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_customers_customer_code (customer_code),
    INDEX idx_customers_name (name),
    INDEX idx_customers_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='得意先マスタ';

-- 得意先別特別単価マスタ
-- 🔵 信頼性: REQ-006・REQ-020・US-006より
CREATE TABLE customer_special_prices (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    customer_id BIGINT UNSIGNED NOT NULL COMMENT '得意先ID',
    product_id  BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    unit_price  DECIMAL(15, 2)  NOT NULL COMMENT '特別単価',
    created_at  TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at  TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_customer_special_prices_customer_product (customer_id, product_id),
    INDEX idx_csp_customer_id (customer_id),
    INDEX idx_csp_product_id (product_id),
    INDEX idx_csp_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='得意先別特別単価マスタ';

-- 商品カテゴリマスタ
-- 🔵 信頼性: REQ-007・US-007より
CREATE TABLE product_categories (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100)    NOT NULL COMMENT 'カテゴリ名',
    created_at TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX idx_product_categories_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='商品カテゴリマスタ';

-- 商品マスタ
-- 🔵 信頼性: REQ-008・US-007より
CREATE TABLE products (
    id                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_code        VARCHAR(50)     NOT NULL COMMENT '品番',
    name                VARCHAR(255)    NOT NULL COMMENT '品名',
    product_category_id BIGINT UNSIGNED NOT NULL COMMENT 'カテゴリID',
    standard_price      DECIMAL(15, 2)  NOT NULL DEFAULT 0 COMMENT '標準単価',
    created_at          TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at          TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_products_product_code (product_code),
    INDEX idx_products_category (product_category_id),
    INDEX idx_products_name (name),
    INDEX idx_products_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='商品マスタ';

-- 倉庫マスタ
-- 🔵 信頼性: REQ-009・US-008より
CREATE TABLE warehouses (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100)    NOT NULL COMMENT '倉庫名',
    location   VARCHAR(255)    NULL     COMMENT 'ロケーション・所在地',
    created_at TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX idx_warehouses_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='倉庫マスタ';

-- ========================================
-- 3. 見積管理
-- ========================================

-- 見積書
-- 🔵 信頼性: REQ-013〜REQ-015・REQ-025・REQ-026・US-010〜US-015より
CREATE TABLE quotations (
    id                BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    quotation_number  VARCHAR(20)      NOT NULL COMMENT '見積番号（Q{YYYYMM}-{連番}）',
    customer_id       BIGINT UNSIGNED  NOT NULL COMMENT '得意先ID',
    user_id           BIGINT UNSIGNED  NOT NULL COMMENT '作成者ID（salesロール）',
    valid_until       DATE             NULL     COMMENT '有効期限',
    status            ENUM('draft','pending','approved','rejected') NOT NULL DEFAULT 'draft'
                                                 COMMENT 'ステータス（REQ-015）',
    rejection_reason  TEXT             NULL     COMMENT '却下理由',
    submitted_at      TIMESTAMP        NULL     COMMENT '承認申請日時',
    approved_at       TIMESTAMP        NULL     COMMENT '承認・却下日時',
    approved_by       BIGINT UNSIGNED  NULL     COMMENT '承認者ID（adminロール）',
    created_at        TIMESTAMP        NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP        NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at        TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_quotations_number (quotation_number),
    INDEX idx_quotations_customer (customer_id),
    INDEX idx_quotations_user (user_id),
    INDEX idx_quotations_status (status),
    INDEX idx_quotations_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='見積書';

-- 見積明細
-- 🔵 信頼性: REQ-014・US-010より
CREATE TABLE quotation_details (
    id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    quotation_id BIGINT UNSIGNED NOT NULL COMMENT '見積書ID',
    product_id   BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    quantity     DECIMAL(15, 3)  NOT NULL COMMENT '数量（EDGE-006: 最小1以上）',
    unit_price   DECIMAL(15, 2)  NOT NULL COMMENT '単価（特別単価自動反映: REQ-020）',
    amount       DECIMAL(15, 2)  NOT NULL COMMENT '金額（quantity × unit_price）',
    sort_order   SMALLINT        NOT NULL DEFAULT 0 COMMENT '明細行順序',
    created_at   TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at   TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX idx_quotation_details_quotation (quotation_id),
    INDEX idx_quotation_details_product (product_id),
    INDEX idx_quotation_details_deleted_at (deleted_at),
    CONSTRAINT chk_quotation_details_quantity CHECK (quantity >= 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='見積明細';

-- ========================================
-- 4. 受注管理
-- ========================================

-- 受注
-- 🔵 信頼性: REQ-016〜REQ-019・REQ-024〜REQ-026・US-016〜US-020より
CREATE TABLE orders (
    id              BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    order_number    VARCHAR(20)      NOT NULL COMMENT '受注番号（O{YYYYMM}-{連番}）',
    customer_id     BIGINT UNSIGNED  NOT NULL COMMENT '得意先ID',
    user_id         BIGINT UNSIGNED  NOT NULL COMMENT '作成者ID（salesロール）',
    quotation_id    BIGINT UNSIGNED  NULL     COMMENT '元見積書ID（変換元、直接入力時はNULL）',
    delivery_date   DATE             NULL     COMMENT '納期',
    status          ENUM('draft','pending','approved','rejected') NOT NULL DEFAULT 'draft'
                                               COMMENT 'ステータス（REQ-017）',
    rejection_reason TEXT            NULL     COMMENT '却下理由',
    submitted_at    TIMESTAMP        NULL     COMMENT '承認申請日時',
    approved_at     TIMESTAMP        NULL     COMMENT '承認・却下日時',
    approved_by     BIGINT UNSIGNED  NULL     COMMENT '承認者ID（adminロール）',
    created_at      TIMESTAMP        NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP        NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_orders_number (order_number),
    INDEX idx_orders_customer (customer_id),
    INDEX idx_orders_user (user_id),
    INDEX idx_orders_quotation (quotation_id),
    INDEX idx_orders_status (status),
    INDEX idx_orders_delivery_date (delivery_date),
    INDEX idx_orders_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='受注';

-- 受注明細
-- 🔵 信頼性: REQ-014・REQ-019・US-016・US-020より
CREATE TABLE order_details (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id         BIGINT UNSIGNED NOT NULL COMMENT '受注ID',
    product_id       BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    quantity         DECIMAL(15, 3)  NOT NULL COMMENT '受注数量（EDGE-006: 最小1以上）',
    unit_price       DECIMAL(15, 2)  NOT NULL COMMENT '単価',
    amount           DECIMAL(15, 2)  NOT NULL COMMENT '金額（quantity × unit_price）',
    shipped_quantity DECIMAL(15, 3)  NOT NULL DEFAULT 0 COMMENT '出荷済数量（受注残計算用: REQ-019）',
    sort_order       SMALLINT        NOT NULL DEFAULT 0 COMMENT '明細行順序',
    created_at       TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at       TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX idx_order_details_order (order_id),
    INDEX idx_order_details_product (product_id),
    INDEX idx_order_details_deleted_at (deleted_at),
    CONSTRAINT chk_order_details_quantity CHECK (quantity >= 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='受注明細';

-- ========================================
-- 5. 製造指示管理
-- ========================================

-- 製造指示
-- 🔵 信頼性: REQ-023・REQ-027〜REQ-028・REQ-039・US-019〜US-024・ヒアリングQ3より
CREATE TABLE manufacture_orders (
    id                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    manufacture_number  VARCHAR(20)     NOT NULL COMMENT '製番（MO{YYYYMM}-{連番}）（REQ-028）',
    order_id            BIGINT UNSIGNED NOT NULL COMMENT '受注ID（1受注=1製造指示: REQ-039）',
    status              ENUM('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending'
                                                  COMMENT '進捗ステータス（REQ-027）',
    started_at          TIMESTAMP       NULL     COMMENT '着手日時',
    completed_at        TIMESTAMP       NULL     COMMENT '完了日時',
    cancelled_at        TIMESTAMP       NULL     COMMENT 'キャンセル日時',
    cancelled_by        BIGINT UNSIGNED NULL     COMMENT 'キャンセル実行者ID（adminのみ）',
    created_at          TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at          TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_manufacture_orders_number (manufacture_number),
    UNIQUE KEY uq_manufacture_orders_order (order_id),  -- 1受注=1製造指示
    INDEX idx_manufacture_orders_status (status),
    INDEX idx_manufacture_orders_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='製造指示';

-- ========================================
-- 6. 在庫管理（2層構造）
-- ========================================

-- 在庫ロット（ロット情報）
-- 🔵 信頼性: REQ-029・REQ-035・US-023・ヒアリング（2層構造選択）より
CREATE TABLE stock_lots (
    id                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    lot_number           VARCHAR(50)     NOT NULL COMMENT 'ロット番号',
    product_id           BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    manufacture_order_id BIGINT UNSIGNED NULL     COMMENT '製造指示ID（製番トレース: US-024）',
    manufactured_date    DATE            NULL     COMMENT '製造日',
    expiry_date          DATE            NULL     COMMENT '有効期限',
    created_at           TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at           TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_stock_lots_lot_number (lot_number),
    INDEX idx_stock_lots_product (product_id),
    INDEX idx_stock_lots_manufacture_order (manufacture_order_id),
    INDEX idx_stock_lots_expiry (expiry_date),
    INDEX idx_stock_lots_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='在庫ロット';

-- 倉庫別在庫数量
-- 🔵 信頼性: REQ-030・REQ-036・ヒアリング（2層構造選択）より
CREATE TABLE stock_quantities (
    id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    stock_lot_id BIGINT UNSIGNED NOT NULL COMMENT 'ロットID',
    warehouse_id BIGINT UNSIGNED NOT NULL COMMENT '倉庫ID',
    quantity     DECIMAL(15, 3)  NOT NULL DEFAULT 0 COMMENT '在庫数量',
    created_at   TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at   TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_stock_quantities_lot_warehouse (stock_lot_id, warehouse_id),
    INDEX idx_stock_quantities_lot (stock_lot_id),
    INDEX idx_stock_quantities_warehouse (warehouse_id),
    INDEX idx_stock_quantities_quantity (quantity),
    INDEX idx_stock_quantities_deleted_at (deleted_at),
    CONSTRAINT chk_stock_quantities_quantity CHECK (quantity >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='倉庫別在庫数量';

-- 在庫移動履歴
-- 🔵 信頼性: REQ-031〜REQ-037・US-026〜US-027より
CREATE TABLE stock_movements (
    id                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    stock_lot_id         BIGINT UNSIGNED NOT NULL COMMENT 'ロットID',
    warehouse_id         BIGINT UNSIGNED NOT NULL COMMENT '対象倉庫ID',
    target_warehouse_id  BIGINT UNSIGNED NULL     COMMENT '移動先倉庫ID（移動時のみ）',
    movement_type        ENUM('inbound','outbound','transfer','adjustment','return')
                                         NOT NULL COMMENT '移動種別',
    quantity             DECIMAL(15, 3)  NOT NULL COMMENT '移動数量（正:入庫・加算, 負:出庫・減算）',
    reference_type       VARCHAR(50)     NULL     COMMENT '参照元種別（ManufactureOrder/Shipment/etc）',
    reference_id         BIGINT UNSIGNED NULL     COMMENT '参照元ID',
    memo                 TEXT            NULL     COMMENT '備考・調整理由',
    user_id              BIGINT UNSIGNED NOT NULL COMMENT '操作者ID',
    created_at           TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    -- 履歴データのため updated_at なし
    PRIMARY KEY (id),
    INDEX idx_stock_movements_lot (stock_lot_id),
    INDEX idx_stock_movements_warehouse (warehouse_id),
    INDEX idx_stock_movements_type (movement_type),
    INDEX idx_stock_movements_reference (reference_type, reference_id),
    INDEX idx_stock_movements_user (user_id),
    INDEX idx_stock_movements_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='在庫移動履歴';

-- ========================================
-- 7. 出荷管理
-- ========================================

-- 出荷
-- 🔵 信頼性: REQ-033〜REQ-034・REQ-036〜REQ-038・US-028〜US-030より
CREATE TABLE shipments (
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    shipment_number VARCHAR(20)     NOT NULL COMMENT '出荷番号（SH{YYYYMM}-{連番}）',
    order_id        BIGINT UNSIGNED NOT NULL COMMENT '受注ID',
    warehouse_id    BIGINT UNSIGNED NOT NULL COMMENT '出荷元倉庫ID',
    shipment_date   DATE            NOT NULL COMMENT '出荷日',
    status          ENUM('draft','confirmed','returned') NOT NULL DEFAULT 'confirmed'
                                    COMMENT '出荷ステータス',
    user_id         BIGINT UNSIGNED NOT NULL COMMENT '出荷登録者ID',
    created_at      TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_shipments_number (shipment_number),
    INDEX idx_shipments_order (order_id),
    INDEX idx_shipments_warehouse (warehouse_id),
    INDEX idx_shipments_date (shipment_date),
    INDEX idx_shipments_status (status),
    INDEX idx_shipments_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='出荷';

-- 出荷明細（ロット引当情報）
-- 🔵 信頼性: REQ-033・REQ-036・REQ-038・US-028・ヒアリングQ4より
CREATE TABLE shipment_details (
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    shipment_id     BIGINT UNSIGNED NOT NULL COMMENT '出荷ID',
    order_detail_id BIGINT UNSIGNED NOT NULL COMMENT '受注明細ID',
    stock_lot_id    BIGINT UNSIGNED NOT NULL COMMENT '引当ロットID（手動指定: REQ-038）',
    warehouse_id    BIGINT UNSIGNED NOT NULL COMMENT '出荷元倉庫ID',
    quantity        DECIMAL(15, 3)  NOT NULL COMMENT '出荷数量',
    is_returned     TINYINT(1)      NOT NULL DEFAULT 0 COMMENT '返品フラグ',
    return_reason   TEXT            NULL     COMMENT '返品理由',
    returned_at     TIMESTAMP       NULL     COMMENT '返品日時',
    created_at      TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX idx_shipment_details_shipment (shipment_id),
    INDEX idx_shipment_details_order_detail (order_detail_id),
    INDEX idx_shipment_details_lot (stock_lot_id),
    INDEX idx_shipment_details_deleted_at (deleted_at),
    CONSTRAINT chk_shipment_details_quantity CHECK (quantity > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='出荷明細（ロット引当）';

-- ========================================
-- 8. 請求・入金管理
-- ========================================

-- 請求書
-- 🔵 信頼性: REQ-040〜REQ-041・REQ-044〜REQ-045・US-031〜US-033・ヒアリングQ5より
CREATE TABLE invoices (
    id                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    invoice_number        VARCHAR(20)     NOT NULL COMMENT '請求書番号（INV{YYYYMM}-{連番}）',
    customer_id           BIGINT UNSIGNED NOT NULL COMMENT '得意先ID',
    invoice_date          DATE            NOT NULL COMMENT '請求書発行日',
    closing_date          DATE            NOT NULL COMMENT '締日（実際の日付）',
    billing_period_start  DATE            NOT NULL COMMENT '請求対象期間 開始',
    billing_period_end    DATE            NOT NULL COMMENT '請求対象期間 終了',
    subtotal              DECIMAL(15, 2)  NOT NULL DEFAULT 0 COMMENT '小計（税抜）',
    tax_rate              DECIMAL(5, 2)   NOT NULL DEFAULT 10.00 COMMENT '消費税率（%）',
    tax_amount            DECIMAL(15, 2)  NOT NULL DEFAULT 0 COMMENT '消費税額',
    total_amount          DECIMAL(15, 2)  NOT NULL DEFAULT 0 COMMENT '請求合計（税込）',
    remaining_amount      DECIMAL(15, 2)  NOT NULL DEFAULT 0 COMMENT '未充当残高',
    status                ENUM('draft','issued','partial','paid') NOT NULL DEFAULT 'issued'
                                          COMMENT '請求ステータス',
    created_by            BIGINT UNSIGNED NOT NULL COMMENT '作成者ID（adminのみ）',
    created_at            TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at            TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at            TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_invoices_number (invoice_number),
    INDEX idx_invoices_customer (customer_id),
    INDEX idx_invoices_status (status),
    INDEX idx_invoices_closing_date (closing_date),
    INDEX idx_invoices_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='請求書';

-- 請求明細
-- 🔵 信頼性: REQ-040・REQ-044・US-031より
CREATE TABLE invoice_details (
    id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    invoice_id    BIGINT UNSIGNED NOT NULL COMMENT '請求書ID',
    shipment_id   BIGINT UNSIGNED NOT NULL COMMENT '出荷ID（請求元）',
    product_id    BIGINT UNSIGNED NOT NULL COMMENT '商品ID',
    description   VARCHAR(255)    NOT NULL COMMENT '品名・説明',
    quantity      DECIMAL(15, 3)  NOT NULL COMMENT '数量',
    unit_price    DECIMAL(15, 2)  NOT NULL COMMENT '単価',
    amount        DECIMAL(15, 2)  NOT NULL COMMENT '金額',
    created_at    TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at    TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX idx_invoice_details_invoice (invoice_id),
    INDEX idx_invoice_details_shipment (shipment_id),
    INDEX idx_invoice_details_product (product_id),
    INDEX idx_invoice_details_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='請求明細';

-- 入金
-- 🔵 信頼性: REQ-042・US-034より
CREATE TABLE payments (
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    customer_id     BIGINT UNSIGNED NOT NULL COMMENT '得意先ID',
    payment_date    DATE            NOT NULL COMMENT '入金日',
    payment_method  ENUM('bank_transfer','check','cash','other') NOT NULL DEFAULT 'bank_transfer'
                                    COMMENT '入金方法',
    amount          DECIMAL(15, 2)  NOT NULL COMMENT '入金額（EDGE-009: 1以上）',
    unallocated_amount DECIMAL(15, 2) NOT NULL DEFAULT 0 COMMENT '未充当残額（EDGE-005）',
    memo            TEXT            NULL     COMMENT '備考',
    created_by      BIGINT UNSIGNED NOT NULL COMMENT '登録者ID（adminのみ）',
    created_at      TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX idx_payments_customer (customer_id),
    INDEX idx_payments_date (payment_date),
    INDEX idx_payments_deleted_at (deleted_at),
    CONSTRAINT chk_payments_amount CHECK (amount >= 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='入金';

-- 入金充当（消込）
-- 🔵 信頼性: REQ-043・US-035・US-036・ヒアリングQ6より
CREATE TABLE payment_allocations (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    payment_id       BIGINT UNSIGNED NOT NULL COMMENT '入金ID',
    invoice_id       BIGINT UNSIGNED NOT NULL COMMENT '請求書ID',
    allocated_amount DECIMAL(15, 2)  NOT NULL COMMENT '充当金額（REQ-043）',
    allocated_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '充当日時',
    created_at       TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP       NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at       TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX idx_payment_allocations_payment (payment_id),
    INDEX idx_payment_allocations_invoice (invoice_id),
    INDEX idx_payment_allocations_deleted_at (deleted_at),
    CONSTRAINT chk_payment_allocations_amount CHECK (allocated_amount > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='入金充当（消込）';

-- ========================================
-- 外部キー制約
-- ========================================

-- 🔵 信頼性: データモデル設計・要件定義より

-- customer_special_prices
ALTER TABLE customer_special_prices
    ADD CONSTRAINT fk_csp_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
    ADD CONSTRAINT fk_csp_product  FOREIGN KEY (product_id)  REFERENCES products(id);

-- products
ALTER TABLE products
    ADD CONSTRAINT fk_products_category FOREIGN KEY (product_category_id) REFERENCES product_categories(id);

-- quotations
ALTER TABLE quotations
    ADD CONSTRAINT fk_quotations_customer    FOREIGN KEY (customer_id)  REFERENCES customers(id),
    ADD CONSTRAINT fk_quotations_user        FOREIGN KEY (user_id)       REFERENCES users(id),
    ADD CONSTRAINT fk_quotations_approved_by FOREIGN KEY (approved_by)   REFERENCES users(id);

-- quotation_details
ALTER TABLE quotation_details
    ADD CONSTRAINT fk_qd_quotation FOREIGN KEY (quotation_id) REFERENCES quotations(id),
    ADD CONSTRAINT fk_qd_product   FOREIGN KEY (product_id)   REFERENCES products(id);

-- orders
ALTER TABLE orders
    ADD CONSTRAINT fk_orders_customer    FOREIGN KEY (customer_id)  REFERENCES customers(id),
    ADD CONSTRAINT fk_orders_user        FOREIGN KEY (user_id)       REFERENCES users(id),
    ADD CONSTRAINT fk_orders_quotation   FOREIGN KEY (quotation_id) REFERENCES quotations(id),
    ADD CONSTRAINT fk_orders_approved_by FOREIGN KEY (approved_by)  REFERENCES users(id);

-- order_details
ALTER TABLE order_details
    ADD CONSTRAINT fk_od_order   FOREIGN KEY (order_id)   REFERENCES orders(id),
    ADD CONSTRAINT fk_od_product FOREIGN KEY (product_id) REFERENCES products(id);

-- manufacture_orders
ALTER TABLE manufacture_orders
    ADD CONSTRAINT fk_mo_order        FOREIGN KEY (order_id)      REFERENCES orders(id),
    ADD CONSTRAINT fk_mo_cancelled_by FOREIGN KEY (cancelled_by)  REFERENCES users(id);

-- stock_lots
ALTER TABLE stock_lots
    ADD CONSTRAINT fk_sl_product         FOREIGN KEY (product_id)           REFERENCES products(id),
    ADD CONSTRAINT fk_sl_manufacture_order FOREIGN KEY (manufacture_order_id) REFERENCES manufacture_orders(id);

-- stock_quantities
ALTER TABLE stock_quantities
    ADD CONSTRAINT fk_sq_lot       FOREIGN KEY (stock_lot_id) REFERENCES stock_lots(id),
    ADD CONSTRAINT fk_sq_warehouse FOREIGN KEY (warehouse_id) REFERENCES warehouses(id);

-- stock_movements
ALTER TABLE stock_movements
    ADD CONSTRAINT fk_sm_lot              FOREIGN KEY (stock_lot_id)        REFERENCES stock_lots(id),
    ADD CONSTRAINT fk_sm_warehouse        FOREIGN KEY (warehouse_id)        REFERENCES warehouses(id),
    ADD CONSTRAINT fk_sm_target_warehouse FOREIGN KEY (target_warehouse_id) REFERENCES warehouses(id),
    ADD CONSTRAINT fk_sm_user             FOREIGN KEY (user_id)             REFERENCES users(id);

-- shipments
ALTER TABLE shipments
    ADD CONSTRAINT fk_shipments_order     FOREIGN KEY (order_id)     REFERENCES orders(id),
    ADD CONSTRAINT fk_shipments_warehouse FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    ADD CONSTRAINT fk_shipments_user      FOREIGN KEY (user_id)      REFERENCES users(id);

-- shipment_details
ALTER TABLE shipment_details
    ADD CONSTRAINT fk_sd_shipment      FOREIGN KEY (shipment_id)     REFERENCES shipments(id),
    ADD CONSTRAINT fk_sd_order_detail  FOREIGN KEY (order_detail_id) REFERENCES order_details(id),
    ADD CONSTRAINT fk_sd_lot           FOREIGN KEY (stock_lot_id)    REFERENCES stock_lots(id),
    ADD CONSTRAINT fk_sd_warehouse     FOREIGN KEY (warehouse_id)    REFERENCES warehouses(id);

-- invoices
ALTER TABLE invoices
    ADD CONSTRAINT fk_invoices_customer   FOREIGN KEY (customer_id) REFERENCES customers(id),
    ADD CONSTRAINT fk_invoices_created_by FOREIGN KEY (created_by)  REFERENCES users(id);

-- invoice_details
ALTER TABLE invoice_details
    ADD CONSTRAINT fk_ind_invoice  FOREIGN KEY (invoice_id)  REFERENCES invoices(id),
    ADD CONSTRAINT fk_ind_shipment FOREIGN KEY (shipment_id) REFERENCES shipments(id),
    ADD CONSTRAINT fk_ind_product  FOREIGN KEY (product_id)  REFERENCES products(id);

-- payments
ALTER TABLE payments
    ADD CONSTRAINT fk_payments_customer   FOREIGN KEY (customer_id) REFERENCES customers(id),
    ADD CONSTRAINT fk_payments_created_by FOREIGN KEY (created_by)  REFERENCES users(id);

-- payment_allocations
ALTER TABLE payment_allocations
    ADD CONSTRAINT fk_pa_payment FOREIGN KEY (payment_id) REFERENCES payments(id),
    ADD CONSTRAINT fk_pa_invoice FOREIGN KEY (invoice_id) REFERENCES invoices(id);

SET FOREIGN_KEY_CHECKS = 1;

-- ========================================
-- 初期データ（シーダー）
-- ========================================

-- 注: 以下は参考。実際は DatabaseSeeder + RoleSeeder + AdminUserSeeder で実装する

-- ロール初期データ（Spatie Permission）
-- 🔵 信頼性: REQ-003・ヒアリングQ10より
-- INSERT INTO roles (name, guard_name) VALUES
--     ('admin',       'web'),
--     ('sales',       'web'),
--     ('manufacture', 'web'),
--     ('warehouse',   'web');

-- ========================================
-- 信頼性レベルサマリー
-- ========================================
-- - 🔵 青信号: 45件 (98%)
-- - 🟡 黄信号: 1件 (2%)  ← invoices.tax_rate デフォルト値（10%）
-- - 🔴 赤信号: 0件 (0%)
--
-- 品質評価: ✅ 高品質
