# セッション記録: 見積書仕様書・テスト体制の整備

日時: 2026-05-17  
担当: Claude Code (claude-sonnet-4-6)

---

## このセッションでやったこと（概要）

1. 未作成画面の一覧確認
2. 見積書一覧の仕様書に必要な項目の検討
3. 承認フローの書き方方針の決定
4. 仕様書・チェックリスト・テストの作成
5. フォルダ構成の整理
6. セッションのアーカイブ

---

## 会話の流れ

### 1. 未作成の画面一覧を確認

**ユーザーの質問:**
> このプロジェクトで現在未作成の画面一覧を教えて

**結論:**
- Phase 1（認証・マスタ管理）は100%完成
- Phase 2〜5（見積・受注・製造・在庫・出荷・請求・入金・レポート）の約37画面が未実装
- 次の実装は見積書管理（TASK-0011以降）から

---

### 2. 見積書一覧の仕様書に必要な項目を検討

**ユーザーの質問:**
> 見積書一覧について人が見ても分かりやすい仕様書を作成したいです。どのような項目が必要でしょうか？

**提案した構成（得意先管理の仕様書をテンプレートに）:**
1. この機能の目的
2. 利用できるユーザー
3. 画面一覧
4. 見積書の登録項目
5. 機能詳細
6. ステータスと承認フロー
7. 他機能との連携
8. エラーと注意事項
9. 用語説明

**追加で検討が必要だった点:**
- 承認フローはどこまで詳しく書くか
- PDF出力の仕様は一覧仕様書に含めるか別ページにするか

---

### 3. 承認フローをどこまで書くかの方針決定

**ユーザーの質問:**
> 承認フローはどこまで詳しく書くかについて解説して

**決定した方針:**

| 一覧仕様書に書く（概要のみ） | 別ページに移す |
|---|---|
| ステータスの種類と日本語名 | 申請操作の画面遷移・ボタン仕様 |
| ステータスの流れ（矢印で簡単に） | メール通知の送信先・本文 |
| 自分のロールで何ができるか | 却下コメントの入力ルール |
| | 誰が承認できるか（admin限定のルール） |

**採用したフォルダ構成:**
```
feature-specs/
├── approval-flow.md          ← 承認フロー共通仕様（見積・受注共通）
├── customer-management/
└── quotation-management/
    ├── quotation-list.md     ← 一覧仕様（詳細はapproval-flow.mdへリンク）
    └── ...
```

---

### 4. 仕様書の作成

**ユーザーの指示:**
> この方針で作成してください。

**作成したファイル:**

#### `approval-flow.md`（承認フロー共通仕様）
- ステータスの種類（draft / pending / approved / rejected）
- ステータス遷移図
- 各ステータスでできる操作（ロール別）
- 承認ルール（一段階承認・admin限定）
- メール通知のタイミングと送信先
- 却下コメントの仕様
- EDGE-001（メール送信失敗時のロールバック）

#### `quotation-list.md`（見積書管理 機能仕様書）
当初は全機能を含む仕様書として作成。

---

### 5. 一覧画面に絞った仕様書に書き直し

**ユーザーの指示:**
> 上記の仕様について、一覧画面の内容だけに絞った仕様書を作成してほしいです。

**quotation-list.md を以下に絞って書き直し:**
- 表示カラムと仕様（見積番号・得意先名・合計金額・有効期限・ステータス・作成者・作成日）
- 検索・絞り込み条件（5項目、AND検索）
- ステータスバッジの色（draft=グレー / pending=黄 / approved=緑 / rejected=赤）
- 操作ボタンの表示制御（編集はdraftのみ、詳細・複製は常時）
- デフォルトソート順（作成日降順）
- ページネーション（20件/ページ）

**削除した内容（別ファイルに移す予定）:**
- 見積書の登録項目（登録・編集画面の仕様）
- 承認申請・受注変換・PDF出力の詳細

---

### 6. 実装〜テストの確認体制を作成

**ユーザーの質問:**
> 他の人がこの仕様書をもとに実装を行った場合に、出来上がったプログラムが仕様書通りに出来ているかを確認する仕組みを作成したいです。

**ユーザーの選択:** 自動テスト（PHPUnit）+ 手動チェックリストの両方

**作成したファイル:**

#### `tests/Feature/Quotation/QuotationIndexTest.php`（自動テスト）
テストケース一覧:
- アクセス制御（5件）: 未認証/admin/sales→OK、manufacture/warehouse→403
- 表示項目（2件）: 各カラム表示確認、新規登録ボタン
- ページネーション（2件）: 1ページ20件、2ページ目の件数
- デフォルトソート（1件）: 作成日降順
- 論理削除（1件）: 削除済みは非表示
- 検索・絞り込み（6件）: 見積番号/得意先名/ステータス/作成日開始・終了/AND検索
- ボタン表示制御（7件）: 編集ボタン（draft以外は非表示）、詳細・複製（常時表示）

**自動テストで確認できること vs 手動チェックリストで確認すること:**

| 自動テスト | 手動チェックリスト |
|---|---|
| アクセス制御（403/200） | バッジの色（グレー/黄/緑/赤） |
| 表示カラム・ページネーション | 有効期限切れの強調表示 |
| 検索条件（AND検索） | ページネーションのUI |
| ボタンの表示/非表示 | 検索0件時のメッセージ文言 |

#### `quotation-list-checklist.md`（手動確認チェックリスト）
- 1〜8章に分類した全確認項目（計約50項目）
- 確認者・確認日・環境の記録欄
- 総合評価欄

#### `implementation-guide.md`（実装〜テストの流れ）
```
Step 1: Red確認（php artisan test → 全件失敗を確認）
Step 2: 実装（マイグレーション → モデル → ルート → コントローラー → ビュー）
Step 3: Green確認（php artisan test → 全件通過を確認）
Step 4: 既存テストへの影響確認（php artisan test）
Step 5: 手動確認チェックリストでブラウザ確認
```

---

### 7. tdd-red → tdd-green の解説

**ユーザーの質問:**
> /tsumiki:tdd-red → /tsumiki:tdd-green について解説して

**要点:**
- `tdd-red`: まだ実装されていない機能に対して「失敗するテスト」を先に書く。全件失敗することを確認してからGreenへ
- `tdd-green`: Redのテストを通すための最小実装をする。綺麗さより「通ること」優先
- 今回は手書きでQuotationIndexTest.phpを作成済みなので、Red相当は完了

---

### 8. フォルダ構成の整理

**ユーザーの指示:**
> `docs/spec/manufacturing-sales-mgmt` フォルダを新しく追加して、仕様書関係のファイルをそのフォルダに移動してほしいです。

**ユーザーの選択:**
- フォルダ名: `feature-specs`
- 移動対象: 画面仕様書のみ（approval-flow.md・customer-management/・quotation-management/）

**移動後の構成:**
```
docs/spec/manufacturing-sales-mgmt/
├── requirements.md          ← 要件定義（そのまま）
├── user-stories.md          ← ユーザーストーリー（そのまま）
├── acceptance-criteria.md
├── interview-record.md
├── note.md
├── prep.md
└── feature-specs/           ← 画面仕様書（新フォルダ）
    ├── approval-flow.md
    ├── customer-management/
    │   └── customer-management.md
    └── quotation-management/
        ├── quotation-list.md
        ├── quotation-list-checklist.md
        └── implementation-guide.md
```

---

## 作成・変更したファイル一覧

| ファイル | 操作 | 内容 |
|---|---|---|
| `docs/spec/manufacturing-sales-mgmt/feature-specs/approval-flow.md` | 新規作成 | 承認フロー共通仕様 |
| `docs/spec/manufacturing-sales-mgmt/feature-specs/quotation-management/quotation-list.md` | 新規作成→書き直し | 見積書一覧画面仕様書 |
| `docs/spec/manufacturing-sales-mgmt/feature-specs/quotation-management/quotation-list-checklist.md` | 新規作成 | 手動確認チェックリスト |
| `docs/spec/manufacturing-sales-mgmt/feature-specs/quotation-management/implementation-guide.md` | 新規作成 | 実装〜テストの流れ |
| `tests/Feature/Quotation/QuotationIndexTest.php` | 新規作成 | PHPUnit自動テスト（24件） |
| `docs/spec/manufacturing-sales-mgmt/feature-specs/` | フォルダ作成+移動 | 画面仕様書を集約 |

---

## 次のアクション

1. `php artisan test tests/Feature/Quotation/QuotationIndexTest.php` でRed（全件失敗）を確認
2. TASK-0011の実装完了後、見積書一覧の実装に着手
3. 実装後に `/tsumiki:tdd-green` でGreenフェーズを進める
4. 受注管理の仕様書も同じ構成で作成する
