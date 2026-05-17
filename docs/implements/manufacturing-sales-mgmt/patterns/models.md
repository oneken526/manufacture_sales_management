# 共通パターン: モデルメソッド

## `Quotation::statusBadge(): array`

**場所**: `app/Models/Quotation.php`  
**追加タスク**: TASK-0014（2026-05-17）

**概要**: 見積書ステータスに対応するバッジの `variant`（色）と `label`（表示名）を返す。

**使い方（Bladeビュー）**:
```blade
@php $badge = $quotation->statusBadge() @endphp
<x-badges.badge :variant="$badge['variant']">{{ $badge['label'] }}</x-badges.badge>
```

**返り値**:
| status | variant | label |
|--------|---------|-------|
| `draft` | `default`（グレー） | 下書き |
| `pending` | `warning`（黄色） | 承認待ち |
| `approved` | `success`（緑） | 承認済み |
| `rejected` | `danger`（赤） | 却下 |

**使用画面**: `quotations/index.blade.php`  
**今後使う予定**: 見積書詳細・承認画面など、ステータスバッジを表示する全画面

---

## `Quotation::generateNextNumber(): string`

**場所**: `app/Models/Quotation.php`  
**追加タスク**: TASK-0015（2026-05-17）

**概要**: 見積番号を `Q{YYYYMM}-{3桁連番}` 形式で採番して返す。論理削除済みを含む同月の最大連番+1を使用。

**使い方（コントローラー）**:
```php
$quotation = Quotation::create([
    'quotation_number' => Quotation::generateNextNumber(),
    ...
]);
```

**採番ルール**:
- 同月内で既存の最大連番+1（例: Q202605-003 の次は Q202605-004）
- 翌月になると 001 からリセット
- `withTrashed()` で論理削除済みも含めて採番するため欠番が生じない

**今後使う予定**: 受注など他のドキュメント番号採番でも同パターンを適用できる

---

## `Quotation::saveDetails(array $details): void`

**場所**: `app/Models/Quotation.php`  
**追加タスク**: TASK-0015（2026-05-17）

**概要**: 見積明細を一括保存する。`amount`（金額）は `quantity × unit_price` で自動計算。`sort_order` はフォームの行順（配列インデックス）を使用。

**使い方（コントローラー）**:
```php
// $request->details は [['product_id'=>1, 'quantity'=>5, 'unit_price'=>1000], ...] の形式
$quotation->saveDetails($request->details);
```

**引数の型**:
```php
// details の各要素
[
    'product_id' => int,
    'quantity'   => numeric,
    'unit_price' => numeric,
]
```

**今後使う予定**: 見積書編集（update）・受注明細保存でも同パターンを適用できる
