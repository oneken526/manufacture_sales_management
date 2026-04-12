# 製造業向け販売管理システム タスク概要

**作成日**: 2026-04-11
**推定工数**: 264時間
**総タスク数**: 40件

## 関連文書

- **要件定義書**: [📋 requirements.md](../spec/manufacturing-sales-mgmt/requirements.md)
- **設計文書**: [📐 architecture.md](../design/manufacturing-sales-mgmt/architecture.md)
- **API仕様**: [🔌 api-endpoints.md](../design/manufacturing-sales-mgmt/api-endpoints.md)
- **データベース設計**: [🗄️ database-schema.sql](../design/manufacturing-sales-mgmt/database-schema.sql)
- **データフロー図**: [🔄 dataflow.md](../design/manufacturing-sales-mgmt/dataflow.md)
- **コンテキストノート**: [📝 note.md](../spec/manufacturing-sales-mgmt/note.md)

## フェーズ構成

| フェーズ | 期間目安 | 成果物 | タスク数 | 工数 |
|---------|---------|--------|----------|------|
| Phase 1 | 8日 | 認証・マスタ管理画面 | 10件 | 60h |
| Phase 2 | 9日 | 見積・受注管理画面 | 10件 | 72h |
| Phase 3 | 8日 | 製造指示・在庫・出荷管理画面 | 9件 | 52h |
| Phase 4 | 4日 | 請求・入金管理画面 | 4件 | 32h |
| Phase 5 | 6日 | PDF・レポート | 7件 | 48h |

## タスク番号管理

**使用済みタスク番号**: TASK-0001 〜 TASK-0040
**次回開始番号**: TASK-0041

---

## Phase 1: 認証・基盤・マスタ管理

**目標**: Laravel Breeze + Spatie Permission による認証基盤、全業務テーブルのマイグレーション、マスタ管理 CRUD の完成
**成果物**: ログイン機能、ロール別ダッシュボード、得意先・商品・倉庫・ユーザー管理画面

### タスク一覧

- [x] [TASK-0001: 開発環境セットアップ（Breeze/Spatie/dompdf/jQuery導入）](TASK-0001.md) - 4h (DIRECT) 🔵
- [x] [TASK-0002: 全業務テーブルマイグレーション作成](TASK-0002.md) - 8h (DIRECT) 🔵
- [x] [TASK-0003: Spatie Permission ロール設定・シーダー](TASK-0003.md) - 4h (DIRECT) 🔵
- [x] [TASK-0004: 共通 Blade レイアウト・ロール別ナビゲーション](TASK-0004.md) - 8h (TDD) 🔵
- [x] [TASK-0005: 認証画面カスタマイズ](TASK-0005.md) - 4h (TDD) 🔵
- [ ] [TASK-0006: 得意先管理 CRUD（CustomerController）](TASK-0006.md) - 8h (TDD) 🔵
- [ ] [TASK-0007: 商品・カテゴリ管理 CRUD（ProductController）](TASK-0007.md) - 8h (TDD) 🔵
- [ ] [TASK-0008: 倉庫管理 CRUD（WarehouseController）](TASK-0008.md) - 4h (TDD) 🔵
- [ ] [TASK-0009: ユーザー管理 CRUD + ロール付与（UserController）](TASK-0009.md) - 4h (TDD) 🔵
- [ ] [TASK-0010: 得意先別特別単価管理（CustomerSpecialPrice CRUD + Ajax）](TASK-0010.md) - 8h (TDD) 🔵

### 依存関係

```
TASK-0001 → TASK-0002
TASK-0002 → TASK-0003
TASK-0003 → TASK-0004
TASK-0004 → TASK-0005
TASK-0004 → TASK-0006
TASK-0004 → TASK-0007
TASK-0004 → TASK-0008
TASK-0004 → TASK-0009
TASK-0006 → TASK-0010
TASK-0007 → TASK-0010
```

---

## Phase 2: 見積・受注管理

**目標**: 採番サービス、メール通知、見積書・受注の CRUD と承認ワークフローの完成
**成果物**: 見積書一覧・作成・承認・受注変換画面、受注一覧・作成・承認画面

### タスク一覧

- [ ] [TASK-0011: NumberingService（採番サービス）](TASK-0011.md) - 4h (TDD) 🔵
- [ ] [TASK-0012: Mailクラス実装（承認申請・承認結果通知）](TASK-0012.md) - 4h (DIRECT) 🔵
- [ ] [TASK-0013: Quotation/QuotationDetail モデル + QuotationService](TASK-0013.md) - 8h (TDD) 🔵
- [ ] [TASK-0014: 見積書一覧・詳細画面](TASK-0014.md) - 8h (TDD) 🔵
- [ ] [TASK-0015: 見積作成・編集画面（jQuery 動的明細行）](TASK-0015.md) - 8h (TDD) 🔵
- [ ] [TASK-0016: 見積承認ワークフロー（申請・承認・却下）](TASK-0016.md) - 8h (TDD) 🔵
- [ ] [TASK-0017: 見積→受注変換・見積複製・見積書 PDF 出力](TASK-0017.md) - 8h (TDD) 🔵
- [ ] [TASK-0018: Order/OrderDetail モデル + OrderService](TASK-0018.md) - 8h (TDD) 🔵
- [ ] [TASK-0019: 受注一覧・詳細・登録・編集画面](TASK-0019.md) - 8h (TDD) 🔵
- [ ] [TASK-0020: 受注承認ワークフロー](TASK-0020.md) - 8h (TDD) 🔵

### 依存関係

```
TASK-0002 → TASK-0011
TASK-0003 → TASK-0012
TASK-0011 → TASK-0013
TASK-0012 → TASK-0013
TASK-0013 → TASK-0014
TASK-0013 → TASK-0015
TASK-0010 → TASK-0015
TASK-0013 → TASK-0016
TASK-0012 → TASK-0016
TASK-0016 → TASK-0017
TASK-0017 → TASK-0018
TASK-0018 → TASK-0019
TASK-0018 → TASK-0020
TASK-0012 → TASK-0020
```

---

## Phase 3: 製造指示・在庫・出荷管理

**目標**: 製造指示・在庫 2 層管理・出荷のサービスと画面の完成
**成果物**: 製造指示一覧・ステータス更新、在庫一覧・倉庫間移動・棚卸、出荷登録・確定・返品

### タスク一覧

- [ ] [TASK-0021: ManufactureOrderService（製番採番・ステータス管理・在庫入庫連動）](TASK-0021.md) - 8h (TDD) 🔵
- [ ] [TASK-0022: 製造指示一覧・詳細・ステータス更新画面](TASK-0022.md) - 8h (TDD) 🔵
- [ ] [TASK-0023: StockLot / StockQuantity / StockMovement モデル + StockService](TASK-0023.md) - 8h (TDD) 🔵
- [ ] [TASK-0024: 在庫一覧・ロット別・倉庫別フィルタ画面](TASK-0024.md) - 8h (TDD) 🔵
- [ ] [TASK-0025: 倉庫間移動画面](TASK-0025.md) - 4h (TDD) 🔵
- [ ] [TASK-0026: 棚卸・在庫調整画面](TASK-0026.md) - 4h (TDD) 🔵
- [ ] [TASK-0027: Shipment / ShipmentDetail モデル + ShipmentService](TASK-0027.md) - 8h (TDD) 🔵
- [ ] [TASK-0028: 出荷登録・一覧・詳細画面（ロット手動選択）](TASK-0028.md) - 8h (TDD) 🔵
- [ ] [TASK-0029: 出荷確定処理・返品処理・納品書 PDF 出力](TASK-0029.md) - 4h (TDD) 🔵

### 依存関係

```
TASK-0018 → TASK-0021
TASK-0021 → TASK-0022
TASK-0002 → TASK-0023
TASK-0023 → TASK-0024
TASK-0024 → TASK-0025
TASK-0024 → TASK-0026
TASK-0018 → TASK-0027
TASK-0023 → TASK-0027
TASK-0011 → TASK-0027
TASK-0027 → TASK-0028
TASK-0028 → TASK-0029
```

---

## Phase 4: 請求・入金管理

**目標**: 月次請求書生成、入金登録・消込の完成
**成果物**: 請求書一覧・生成・PDF、入金登録・消込画面

### タスク一覧

- [ ] [TASK-0030: Invoice/InvoiceDetail モデル + InvoiceService（月次請求書生成・締日処理）](TASK-0030.md) - 8h (TDD) 🔵
- [ ] [TASK-0031: 請求書一覧・詳細・月次生成画面・売掛金元帳](TASK-0031.md) - 8h (TDD) 🔵
- [ ] [TASK-0032: Payment / PaymentAllocation モデル + PaymentService](TASK-0032.md) - 8h (TDD) 🔵
- [ ] [TASK-0033: 入金登録・一覧・消込画面](TASK-0033.md) - 8h (TDD) 🔵

### 依存関係

```
TASK-0029 → TASK-0030
TASK-0011 → TASK-0030
TASK-0030 → TASK-0031
TASK-0030 → TASK-0032
TASK-0011 → TASK-0032
TASK-0031 → TASK-0033
TASK-0032 → TASK-0033
```

---

## Phase 5: PDF・レポート

**目標**: 全 PDF テンプレートとレポート機能の完成
**成果物**: PdfService、見積書/納品書/請求書 PDF、売上/在庫/受注残レポート + CSV

### タスク一覧

- [ ] [TASK-0034: PdfService（dompdf ラッパー・スナップショット対応）](TASK-0034.md) - 4h (DIRECT) 🔵
- [ ] [TASK-0035: 見積書 PDF Blade テンプレート](TASK-0035.md) - 4h (TDD) 🔵
- [ ] [TASK-0036: 納品書 PDF Blade テンプレート](TASK-0036.md) - 4h (TDD) 🔵
- [ ] [TASK-0037: 請求書 PDF Blade テンプレート](TASK-0037.md) - 4h (TDD) 🔵
- [ ] [TASK-0038: 売上集計レポート（月次・得意先別・商品別）+ CSV エクスポート](TASK-0038.md) - 8h (TDD) 🔵
- [ ] [TASK-0039: 在庫推移レポート + CSV エクスポート](TASK-0039.md) - 8h (TDD) 🔵
- [ ] [TASK-0040: 受注残レポート（納期超過強調）+ CSV エクスポート](TASK-0040.md) - 8h (TDD) 🔵

### 依存関係

```
TASK-0001 → TASK-0034
TASK-0034 → TASK-0035
TASK-0034 → TASK-0036
TASK-0034 → TASK-0037
TASK-0029 → TASK-0038
TASK-0023 → TASK-0039
TASK-0019 → TASK-0040
```

---

## 全体進捗

- [ ] Phase 1: 認証・基盤・マスタ管理（10件 / 60h）
- [ ] Phase 2: 見積・受注管理（10件 / 72h）
- [ ] Phase 3: 製造指示・在庫・出荷管理（9件 / 52h）
- [ ] Phase 4: 請求・入金管理（4件 / 32h）
- [ ] Phase 5: PDF・レポート（7件 / 48h）

## マイルストーン

- **M1: 認証・マスタ基盤完成** (Phase 1 完了): 全スタッフがログインできる状態
- **M2: 見積・受注 MVP 完成** (Phase 2 完了): 見積から受注承認まで一気通貫で動作
- **M3: 製造・在庫・出荷完成** (Phase 3 完了): 製造指示から出荷確定まで動作
- **M4: 請求・入金完成** (Phase 4 完了): 月次請求書生成・消込まで動作
- **M5: 全機能リリース準備完了** (Phase 5 完了): PDF・レポートを含む全機能動作確認済み

---

## 信頼性レベルサマリー

### 全タスク統計

- **総タスク数**: 40件
- 🔵 **青信号**: 40件 (100%)
- 🟡 **黄信号**: 0件 (0%)
- 🔴 **赤信号**: 0件 (0%)

### フェーズ別信頼性

| フェーズ | 🔵 青 | 🟡 黄 | 🔴 赤 | 合計 |
|---------|-------|-------|-------|------|
| Phase 1 | 10 | 0 | 0 | 10 |
| Phase 2 | 10 | 0 | 0 | 10 |
| Phase 3 | 9  | 0 | 0 | 9  |
| Phase 4 | 4  | 0 | 0 | 4  |
| Phase 5 | 7  | 0 | 0 | 7  |

**品質評価**: ✅ 高品質（全タスク 🔵 青信号）

## クリティカルパス

```
TASK-0001 → TASK-0002 → TASK-0003 → TASK-0004
          → TASK-0011 → TASK-0013 → TASK-0016 → TASK-0017
          → TASK-0018 → TASK-0021 → TASK-0022
                      → TASK-0027 → TASK-0029 → TASK-0030 → TASK-0032 → TASK-0033
```

**クリティカルパス工数**: 約 76h
**並行作業可能工数**: 約 188h

## 次のステップ

タスクを実装するには:
- 全タスク順番に実装: `/tsumiki:kairo-implement`
- 特定タスクを実装: `/tsumiki:kairo-implement TASK-0001`
