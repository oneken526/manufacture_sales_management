# Refactor フェーズ記録: quotation-index

タスクID: TASK-0014  
機能名: 見積書一覧画面  
実施日: 2026-05-17

---

## 改善一覧

| # | 改善内容 | 対象ファイル | 観点 |
|---|---------|------------|------|
| 1 | `statusBadge()` メソッドをモデルに移動（ビューの `@php` ブロック除去） | `app/Models/Quotation.php` | 単一責任原則 |
| 2 | ルートURLを `duplicate` → `copy` に統一（ビューとの不整合を修正） | `routes/web.php` | URL整合性 |
| 3 | ビューのハードコードURLを named routes（`route()` ヘルパー）に置換 | `resources/views/quotations/index.blade.php` | 保守性 |

---

## 改善詳細

### 1. statusBadge() メソッドをモデルへ移動

**問題**: ステータス→バッジ変換ロジックがビュー内の `@php` ブロックに記述されており、
他の画面（詳細・承認画面など）で同じロジックが必要になるたびにコピーが発生する恐れがあった。

**修正内容** (`app/Models/Quotation.php`):

```php
/** ステータスバッジの表示設定（variant・label）を返す 🔵 仕様書 §5 */
public function statusBadge(): array
{
    return match($this->status) {
        'draft'    => ['variant' => 'default', 'label' => '下書き'],
        'pending'  => ['variant' => 'warning', 'label' => '承認待ち'],
        'approved' => ['variant' => 'success', 'label' => '承認済み'],
        'rejected' => ['variant' => 'danger',  'label' => '却下'],
        default    => ['variant' => 'default', 'label' => $this->status],
    };
}
```

**Before（ビュー内）**:
```blade
@php
    $badgeMap = [
        'draft'    => ['variant' => 'default',  'label' => '下書き'],
        'pending'  => ['variant' => 'warning',  'label' => '承認待ち'],
        'approved' => ['variant' => 'success',  'label' => '承認済み'],
        'rejected' => ['variant' => 'danger',   'label' => '却下'],
    ];
    $badge = $badgeMap[$quotation->status] ?? ['variant' => 'default', 'label' => $quotation->status];
@endphp
```

**After（ビュー内）**:
```blade
@php $badge = $quotation->statusBadge() @endphp
```

---

### 2. ルートURL の不整合修正

**問題**: Greenフェーズで `routes/web.php` に `/duplicate` として登録したが、
ビューは `/copy` を使っていた。テストは文字列一致のみ確認するため気付きにくく、
実際にフォーム送信すると 404 になる状態だった。

**修正内容** (`routes/web.php`):

```php
// Before
Route::post('quotations/{quotation}/duplicate', [QuotationController::class, 'duplicate'])
    ->name('quotations.duplicate');

// After
Route::post('quotations/{quotation}/copy', [QuotationController::class, 'copy'])
    ->name('quotations.copy');
```

---

### 3. Named routes への置換

**問題**: ビュー内のURLが `/quotations/create` のように文字列でハードコードされており、
ルート定義を変更したときにビューも修正が必要になる。

**修正内容** (`resources/views/quotations/index.blade.php`):

```blade
{{-- Before --}}
<a href="/quotations/create">
<form action="/quotations">
<a href="/quotations/{{ $quotation->id }}">
<a href="/quotations/{{ $quotation->id }}/edit">
<form action="/quotations/{{ $quotation->id }}/copy">

{{-- After --}}
<x-buttons.link-button :href="route('quotations.create')">
<form action="{{ route('quotations.index') }}">
<a href="{{ route('quotations.show', $quotation, false) }}">
<a href="{{ route('quotations.edit', $quotation, false) }}">
<form action="{{ route('quotations.copy', $quotation) }}">
```

**注意点**: `route()` はデフォルトで絶対URL（`http://localhost/quotations/1`）を生成するが、
テストは相対URL（`/quotations/1`）で `assertSee` している。
第3引数に `false` を渡すと相対URLが生成されるため、これを show・edit リンクに適用した。

---

## セキュリティレビュー結果

| 観点 | 結果 | 備考 |
|------|------|------|
| SQLインジェクション | ✅ 問題なし | Eloquent のパラメータバインディング使用 |
| XSS | ✅ 問題なし | Blade `{{ }}` で自動エスケープ |
| CSRF | ✅ 問題なし | 複製フォームに `@csrf` 付与済み |
| 認可 | ✅ 問題なし | `role:admin|sales` ミドルウェアをルートに適用 |
| マスアサインメント | ✅ 問題なし | `$fillable` で制御済み |

---

## パフォーマンスレビュー結果

| 観点 | 結果 | 備考 |
|------|------|------|
| N+1問題 | ✅ 問題なし | `with(['customer', 'user'])` で Eager Loading 済み |
| ページネーション | ✅ 問題なし | `paginate(20)` で大量データも安全 |
| 得意先名検索 | 🟡 要注意 | `whereHas` はサブクエリになるため、大規模データでは全文検索インデックスが有効。現フェーズでは許容範囲 |

---

## テスト結果

```
Tests: 75 passed (156 assertions)
Duration: 3.35s
```

リファクタ前後ともに全テスト通過。

---

## 品質評価

✅ 高品質

- テスト: 全75件通過
- セキュリティ: 重大な脆弱性なし
- パフォーマンス: 重大な課題なし
- コード品質: 単一責任・named routes・URL整合性を改善
