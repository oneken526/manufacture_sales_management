# 共通パターン: ルーティング

## 相対URL の named route

**追加タスク**: TASK-0014（2026-05-17）

`route()` はデフォルトで絶対URL（`http://localhost/xxx/1`）を生成する。
テストの `assertSee("href=\"/xxx/1\"")` が失敗する場合は第3引数に `false` を渡す。

```blade
{{-- ❌ 絶対URL → テストで assertSee("/quotations/1") が失敗 --}}
{{ route('quotations.show', $quotation) }}

{{-- ✅ 相対URL → テストで assertSee("/quotations/1") が通る --}}
{{ route('quotations.show', $quotation, false) }}
```

**適用ルール**:
- `href` 属性でテストが `assertSee("href=\"/..."` 形式で検証 → `false` を付ける
- `action` 属性（フォーム送信先）→ テストで相対URL検証しないことが多いので省略可
