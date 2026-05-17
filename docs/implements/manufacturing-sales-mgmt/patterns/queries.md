# 共通パターン: クエリ・コントローラ・バリデーション

## 複数条件 AND 検索

**追加タスク**: TASK-0014（2026-05-17）  
**使用例**: `QuotationController::index()`

```php
$query = SomeModel::with(['relation1', 'relation2']);

// テキスト部分一致
if ($value = $request->input('field_name')) {
    $query->where('column', 'like', "%{$value}%");
}

// リレーション先の部分一致（whereHas）
if ($value = $request->input('related_name')) {
    $query->whereHas('relation', fn ($q) => $q->where('name', 'like', "%{$value}%"));
}

// 完全一致
if ($value = $request->input('status')) {
    $query->where('status', $value);
}

// 日付範囲
if ($from = $request->input('created_from')) {
    $query->whereDate('created_at', '>=', $from);
}
if ($to = $request->input('created_to')) {
    $query->whereDate('created_at', '<=', $to);
}

// ページネーション（検索パラメータを次ページに引き継ぐ）
$items = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
```

---

## ソフトデリート除外の存在チェック（FormRequest）

**追加タスク**: TASK-0015（2026-05-17）  
**使用例**: `QuotationRequest::rules()`

`exists:table,column` の文字列ルールはソフトデリート済みレコードも通してしまう。  
`Rule::exists(Model::class, 'id')` を使うとモデルのグローバルスコープ（SoftDeletes）が適用され、論理削除済みを自動で除外できる。

```php
use Illuminate\Validation\Rule;

return [
    // ❌ 文字列ルール: deleted_at を無視してしまう
    // 'customer_id' => ['required', 'exists:customers,id'],

    // ✅ Rule::exists: SoftDeletes スコープが適用される
    'customer_id'          => ['required', 'integer', Rule::exists(Customer::class, 'id')],
    'details.*.product_id' => ['required', 'integer', Rule::exists(Product::class, 'id')],
];
```

**ポイント**: 外部キー参照を持つすべての `exists` バリデーションで、ソフトデリート対応モデルには必ずこのパターンを使うこと。
