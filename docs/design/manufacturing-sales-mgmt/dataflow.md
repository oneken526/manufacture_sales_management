# 製造業向け販売管理システム データフロー図

**作成日**: 2026-04-11
**関連アーキテクチャ**: [architecture.md](architecture.md)
**関連要件定義**: [requirements.md](../../spec/manufacturing-sales-mgmt/requirements.md)

**【信頼性レベル凡例】**:
- 🔵 **青信号**: EARS要件定義書・設計文書・ユーザヒアリングを参考にした確実なフロー
- 🟡 **黄信号**: EARS要件定義書・設計文書・ユーザヒアリングから妥当な推測によるフロー
- 🔴 **赤信号**: EARS要件定義書・設計文書・ユーザヒアリングにない推測によるフロー

---

## システム全体の業務フロー 🔵

**信頼性**: 🔵 *要件定義書概要・ユーザーストーリー全エピックより*

```mermaid
flowchart LR
    A[得意先マスタ<br/>商品マスタ] --> B[見積書作成]
    B -->|承認| C[受注登録]
    C -->|承認| D[製造指示発行]
    D -->|完了| E[在庫入庫]
    C --> F[出荷登録]
    E --> F
    F --> G[月次請求書生成]
    G --> H[入金登録]
    H --> I[消込（充当）]
```

---

## フロー1: 認証・ロール管理 🔵

**信頼性**: 🔵 *REQ-001・REQ-002・REQ-011・REQ-012 より*

**関連要件**: REQ-001, REQ-002, REQ-011, REQ-012

```mermaid
sequenceDiagram
    participant U as ユーザー
    participant B as Blade/Controller
    participant Auth as Laravel Breeze
    participant DB as MySQL

    U->>B: GET /login
    B-->>U: ログイン画面
    U->>B: POST /login（email + password）
    B->>Auth: 認証処理
    Auth->>DB: users テーブル照合
    DB-->>Auth: 照合結果

    alt 認証成功
        Auth->>DB: sessions テーブルに保存
        Auth-->>B: セッション確立
        B->>DB: Spatie Permission でロール取得
        B-->>U: ロール別ダッシュボードへリダイレクト
    else 認証失敗
        Auth-->>B: エラー
        B-->>U: エラーメッセージ表示
    end
```

**ロール別リダイレクト先**:
- `admin` → `/dashboard/admin`（全体サマリー）
- `sales` → `/dashboard/sales`（見積・受注一覧）
- `manufacture` → `/dashboard/manufacture`（製造指示一覧）
- `warehouse` → `/dashboard/warehouse`（出荷待ち・在庫アラート）

---

## フロー2: 見積書作成・承認 🔵

**信頼性**: 🔵 *REQ-013〜REQ-026・US-010〜US-015・ヒアリングQ1・Q7 より*

**関連要件**: REQ-013, REQ-014, REQ-015, REQ-020, REQ-021, REQ-022, REQ-025, REQ-026

```mermaid
sequenceDiagram
    participant S as sales（営業）
    participant C as Controller/Service
    participant DB as MySQL
    participant A as admin（管理者）
    participant MAIL as メールサーバー

    S->>C: GET /quotations/create（見積作成画面）
    C->>DB: 得意先・商品リスト取得
    DB-->>C: マスタデータ
    C-->>S: 作成フォーム表示

    S->>C: 得意先選択（Ajax / フォーム）
    C->>DB: customer_special_prices から特別単価取得
    DB-->>C: 特別単価
    C-->>S: 単価を明細行に自動反映（REQ-020）

    S->>C: POST /quotations（見積書保存）
    C->>DB: quotations + quotation_details 登録（status: draft）
    DB-->>C: 保存完了
    C-->>S: 詳細画面へリダイレクト

    S->>C: POST /quotations/{id}/submit（承認申請）
    Note over C: DB トランザクション開始
    C->>DB: status を pending に更新
    C->>MAIL: 同期メール送信（admin へ承認依頼）（REQ-021）

    alt メール送信成功
        Note over C: トランザクションコミット
        C-->>S: 申請完了メッセージ
    else メール送信失敗
        Note over C: トランザクションロールバック（EDGE-001）
        C-->>S: エラーメッセージ
    end

    A->>C: POST /quotations/{id}/approve（承認）
    C->>DB: status を approved に更新
    C->>MAIL: 同期メール送信（申請者へ承認結果）（REQ-022）
    C-->>A: 承認完了

    alt 却下の場合
        A->>C: POST /quotations/{id}/reject（却下・理由入力）
        C->>DB: status を rejected に更新・rejection_reason 保存
        C->>MAIL: 同期メール送信（申請者へ却下通知）
        C-->>A: 却下完了
    end
```

---

## フロー3: 受注登録・承認・製造指示自動発行 🔵

**信頼性**: 🔵 *REQ-016〜REQ-019・REQ-023〜REQ-025・US-016〜US-019・ヒアリングQ1・Q3・Q7・Q8 より*

**関連要件**: REQ-016, REQ-017, REQ-018, REQ-019, REQ-023, REQ-024, REQ-025

```mermaid
sequenceDiagram
    participant S as sales
    participant C as Controller/Service
    participant OS as OrderService
    participant MS as ManufactureOrderService
    participant DB as MySQL
    participant A as admin
    participant MAIL as メールサーバー

    Note over S: 承認済み見積から受注へ変換（US-015）
    S->>C: POST /quotations/{id}/convert-to-order
    C->>OS: 見積内容を受注にコピー
    OS->>DB: orders + order_details 登録（status: draft）
    DB-->>C: 保存完了
    C-->>S: 受注詳細画面へリダイレクト

    Note over S: 与信限度チェック（REQ-024・EDGE-007）
    S->>C: POST /orders（受注保存 or 編集）
    C->>OS: 与信チェック実行
    OS->>DB: 得意先の与信限度額と現在の売掛金合計を取得
    DB-->>OS: 残与信額

    alt 与信超過（credit_limit > 0 の場合のみ）
        OS-->>C: 与信超過フラグ
        C-->>S: 警告メッセージを表示（保存は続行）
    end

    C->>DB: orders + order_details 登録/更新
    C-->>S: 詳細画面

    S->>C: POST /orders/{id}/submit（承認申請）
    Note over C,OS: DB トランザクション
    C->>DB: status を pending に更新
    C->>MAIL: 同期メール送信（admin へ）
    Note over C: トランザクションコミット or ロールバック（EDGE-001）

    A->>C: POST /orders/{id}/approve（承認）
    Note over C: DB トランザクション開始
    C->>DB: orders.status を approved に更新
    C->>MS: 製造指示を自動生成（REQ-023）
    MS->>DB: NumberingService で製番採番（MO{YYYYMM}-{連番}）
    MS->>DB: manufacture_orders 登録（status: pending）
    C->>MAIL: 同期メール送信（申請者へ承認通知）
    Note over C: トランザクションコミット
    C-->>A: 承認・製造指示発行完了
```

---

## フロー4: 製造指示・在庫入庫 🔵

**信頼性**: 🔵 *REQ-027〜REQ-029・REQ-035・US-021〜US-023・ヒアリングQ3 より*

**関連要件**: REQ-027, REQ-028, REQ-029, REQ-035, REQ-039

```mermaid
sequenceDiagram
    participant MF as manufacture（製造担当）
    participant C as Controller/Service
    participant SS as StockService
    participant DB as MySQL

    MF->>C: GET /manufacture-orders（製造指示一覧）
    C->>DB: manufacture_orders 取得（status: pending/in_progress）
    DB-->>C: 製造指示リスト
    C-->>MF: 一覧表示

    MF->>C: POST /manufacture-orders/{id}/start（着手）
    C->>DB: status を in_progress に更新・started_at 記録
    C-->>MF: ステータス更新完了

    MF->>C: POST /manufacture-orders/{id}/complete（完了）
    Note over MF: ロット番号・製造日・有効期限・数量・入庫倉庫を入力
    Note over C,SS: DB トランザクション開始
    C->>DB: status を completed に更新・completed_at 記録
    C->>SS: 在庫入庫処理（REQ-035）
    SS->>DB: stock_lots 登録（ロット情報）
    SS->>DB: stock_quantities 登録（倉庫別数量）
    SS->>DB: stock_movements 登録（movement_type: inbound）
    Note over C: トランザクションコミット
    C-->>MF: 完了・入庫完了メッセージ

    Note over MF: cancelled への変更は admin のみ（US-022）
    Note over MF: 重複 completed 操作は警告表示・スキップ（EDGE-010）
```

---

## フロー5: 出荷登録・在庫引当 🔵

**信頼性**: 🔵 *REQ-033・REQ-036・REQ-038・US-028・ヒアリングQ4 より*

**関連要件**: REQ-033, REQ-036, REQ-038, EDGE-002

```mermaid
sequenceDiagram
    participant W as warehouse（倉庫担当）
    participant C as Controller/Service
    participant SS as ShipmentService
    participant StS as StockService
    participant DB as MySQL

    W->>C: GET /shipments/create?order_id={id}（出荷登録画面）
    C->>DB: 受注明細・在庫ロット一覧を取得
    DB-->>C: 受注情報・在庫情報
    C-->>W: 出荷フォーム表示（ロット手動選択）

    W->>C: POST /shipments（出荷登録確定）
    Note over W: 倉庫・ロット・数量を手動指定（REQ-038）
    Note over C,SS: DB トランザクション開始
    C->>SS: 出荷登録処理

    SS->>DB: 指定ロットの在庫数量確認（EDGE-002）
    alt 在庫数量不足
        SS-->>C: エラー
        C-->>W: エラーメッセージ（在庫不足）
        Note over C: トランザクションロールバック
    else 在庫数量充足
        SS->>DB: shipments 登録（status: confirmed）
        SS->>DB: shipment_details 登録（ロット引当情報）
        SS->>StS: 在庫数量を減算（REQ-036）
        StS->>DB: stock_quantities.quantity 減算
        StS->>DB: stock_movements 登録（movement_type: outbound）
        SS->>DB: order_details.shipped_quantity 更新（受注残計算）
        Note over C: トランザクションコミット
        C-->>W: 出荷完了・納品書PDF リンク表示
    end
```

---

## フロー6: 返品処理 🔵

**信頼性**: 🔵 *REQ-034・REQ-037・US-030 より*

**関連要件**: REQ-034, REQ-037

```mermaid
sequenceDiagram
    participant W as warehouse/admin
    participant C as Controller/Service
    participant SS as StockService
    participant DB as MySQL

    W->>C: POST /shipments/{id}/return（返品登録）
    Note over W: 返品数量・返品理由を入力
    Note over C,SS: DB トランザクション開始
    C->>DB: 返品レコード登録（shipment_details への参照）
    C->>SS: 在庫加算処理（REQ-037）
    SS->>DB: stock_quantities.quantity 加算
    SS->>DB: stock_movements 登録（movement_type: return）
    SS->>DB: order_details.shipped_quantity 減算（受注残に戻す）
    Note over C: トランザクションコミット
    C-->>W: 返品登録完了
```

---

## フロー7: 月次請求書生成 🔵

**信頼性**: 🔵 *REQ-040・REQ-044・REQ-045・US-031・ヒアリングQ5 より*

**関連要件**: REQ-040, REQ-044, REQ-045, EDGE-004

```mermaid
sequenceDiagram
    participant A as admin
    participant C as Controller/Service
    participant IS as InvoiceService
    participant DB as MySQL

    A->>C: POST /invoices/generate（月次請求書生成）
    Note over A: 対象得意先・対象月を指定（手動操作: REQ-045）

    C->>IS: 請求書生成処理
    IS->>DB: 同一得意先・同一締め月の既存請求書確認

    alt 重複あり（EDGE-004）
        IS-->>C: 重複警告フラグ
        C-->>A: 確認ダイアログ表示（上書き確認）

        alt 管理者が上書き確認
            IS->>DB: 既存請求書を削除（ソフトデリート）
        else 管理者がキャンセル
            C-->>A: 処理中断
        end
    end

    IS->>DB: 対象期間の出荷データ（shipments + shipment_details）取得
    Note over IS: 締日計算（closing_day=99 は月末）
    IS->>DB: NumberingService で請求書番号採番（INV{YYYYMM}-{連番}）
    IS->>DB: invoices 登録（subtotal, tax_amount, total_amount 計算）
    IS->>DB: invoice_details 登録（出荷データを元に）
    IS->>DB: 売掛金元帳（accounts_receivable 相当）更新
    C-->>A: 生成完了・請求書詳細へリダイレクト
```

---

## フロー8: 入金登録・消込 🔵

**信頼性**: 🔵 *REQ-042・REQ-043・US-034・US-035・US-036・ヒアリングQ6 より*

**関連要件**: REQ-042, REQ-043, EDGE-005, EDGE-009

```mermaid
sequenceDiagram
    participant A as admin
    participant C as Controller/Service
    participant PS as PaymentService
    participant DB as MySQL

    A->>C: POST /payments（入金登録）
    Note over A: 得意先・入金日・入金額・入金方法を入力（EDGE-009: 金額1以上）
    C->>DB: payments 登録

    A->>C: POST /payments/{id}/allocate（充当・消込）
    Note over A: 複数請求書を選択し充当金額を入力（REQ-043）
    Note over C,PS: DB トランザクション開始
    C->>PS: 充当処理

    PS->>DB: 充当額の合計が入金額以内か確認
    alt 超過あり
        PS-->>C: バリデーションエラー
        C-->>A: エラーメッセージ
    else 充当額OK
        PS->>DB: payment_allocations 登録（各請求書への充当額）
        PS->>DB: invoices.remaining_amount 更新（残高計算）
        Note over PS: 超過分は未充当として管理（EDGE-005）
        Note over C: トランザクションコミット
        C-->>A: 消込完了
    end
```

---

## フロー9: 在庫移動・棚卸調整 🔵

**信頼性**: 🔵 *REQ-030・REQ-031・REQ-032・US-026・US-027 より*

**関連要件**: REQ-030, REQ-031, REQ-032

```mermaid
sequenceDiagram
    participant W as warehouse/admin
    participant C as Controller/Service
    participant StS as StockService
    participant DB as MySQL

    Note over W: 倉庫間移動
    W->>C: POST /stocks/transfer（在庫移動）
    Note over C,StS: DB トランザクション
    StS->>DB: 移動元 stock_quantities の数量確認
    StS->>DB: 移動元 stock_quantities 減算
    StS->>DB: 移動先 stock_quantities 加算（なければ新規登録）
    StS->>DB: stock_movements 登録（movement_type: transfer）
    Note over C: トランザクションコミット

    Note over W: 棚卸・在庫調整
    W->>C: POST /stocks/adjust（在庫調整）
    Note over W: 実棚卸数量・調整理由を入力
    Note over C,StS: DB トランザクション
    StS->>DB: 現在数量と差分を計算
    StS->>DB: stock_quantities 更新（実棚卸数量に変更）
    StS->>DB: stock_movements 登録（movement_type: adjustment, difference 記録）
    Note over C: トランザクションコミット
    C-->>W: 調整完了
```

---

## フロー10: PDF 出力 🔵

**信頼性**: 🔵 *REQ-046〜REQ-048・REQ-052・REQ-053・ヒアリングQ2 より*

**関連要件**: REQ-046, REQ-047, REQ-048, REQ-052, REQ-053, EDGE-003

```mermaid
sequenceDiagram
    participant U as ユーザー
    participant C as Controller
    participant PS as PdfService
    participant DP as barryvdh/dompdf
    participant DB as MySQL

    U->>C: GET /quotations/{id}/pdf
    Note over C,DP: PDF 生成開始時点でデータスナップショット取得（EDGE-003）
    C->>DB: 該当データ取得（スナップショット）
    C->>PS: PDF 生成依頼
    PS->>DP: Blade テンプレート + データを dompdf に渡す
    DP-->>PS: PDF バイナリ
    PS-->>C: PDF レスポンス
    C-->>U: PDF ダウンロード（日本語のみ: REQ-052）
```

**PDF 対象**:
- 見積書 PDF: `GET /quotations/{id}/pdf`
- 納品書 PDF: `GET /shipments/{id}/pdf`
- 請求書 PDF: `GET /invoices/{id}/pdf`

---

## データ状態遷移

### 見積書・受注ステータス遷移 🔵

**信頼性**: 🔵 *REQ-015・REQ-017・REQ-025・REQ-026 より*

```mermaid
stateDiagram-v2
    [*] --> draft: 新規作成
    draft --> pending: 承認申請（sales）<br/>admin へメール送信
    pending --> approved: 承認（admin）<br/>申請者へメール送信
    pending --> rejected: 却下（admin）<br/>申請者へメール送信（理由付き）
    rejected --> draft: 再編集可能
    approved --> [*]

    note right of pending
        編集不可（REQ-026）
    end note
```

### 製造指示ステータス遷移 🔵

**信頼性**: 🔵 *REQ-027・US-022 より*

```mermaid
stateDiagram-v2
    [*] --> pending: 受注承認時に自動発行
    pending --> in_progress: 着手（manufacture）
    in_progress --> completed: 完了（manufacture）<br/>在庫入庫が自動実行
    pending --> cancelled: キャンセル（admin のみ）
    in_progress --> cancelled: キャンセル（admin のみ）
```

---

## エラーハンドリングフロー 🔵

**信頼性**: 🔵 *EDGE-001〜EDGE-010・REQ-059 より*

```mermaid
flowchart TD
    A[処理実行] --> B{エラー種別}
    B -->|バリデーションエラー| C[422: フォームにエラー表示]
    B -->|認証エラー| D[401: ログインへリダイレクト]
    B -->|権限エラー| E[403: アクセス拒否表示]
    B -->|リソース未存在| F[404: Not Found]
    B -->|在庫不足 EDGE-002| G[エラーメッセージ表示<br/>引当拒否]
    B -->|メール送信失敗 EDGE-001| H[DBロールバック<br/>エラーメッセージ表示]
    B -->|サーバーエラー| I[500: ログ記録<br/>エラー画面表示]
```

---

## 関連文書

- **アーキテクチャ**: [architecture.md](architecture.md)
- **DBスキーマ**: [database-schema.sql](database-schema.sql)
- **Webルート仕様**: [api-endpoints.md](api-endpoints.md)
- **要件定義**: [requirements.md](../../spec/manufacturing-sales-mgmt/requirements.md)

---

## 信頼性レベルサマリー

- 🔵 青信号: 20件 (100%)
- 🟡 黄信号: 0件 (0%)
- 🔴 赤信号: 0件 (0%)

**品質評価**: ✅ 高品質
