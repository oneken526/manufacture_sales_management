# 共通パターン: Blade コンポーネント

実装時は既存コンポーネントを優先して使うこと。

## コンポーネント一覧

| コンポーネント | タグ | variant 一覧 |
|--------------|-----|-------------|
| バッジ | `<x-badges.badge>` | `default` / `success` / `warning` / `danger` / `indigo` / `emerald` / `amber` / `info` |
| リンクボタン | `<x-buttons.link-button>` | `ghost` |
| テーブル操作ボタン | `<x-buttons.table-action>` | `edit` / `delete` |
| 検索ボタン | `<x-buttons.search-button>` | — |
| テキスト入力 | `<x-inputs.text-input>` | `search` |
| セレクト | `<x-inputs.select>` | — |
| ラベル | `<x-inputs.input-label>` | — |
| エラー表示 | `<x-inputs.input-error>` | — |
| テキストエリア | `<x-inputs.textarea>` | — |

## 使い方の例

### ステータスバッジ
```blade
<x-badges.badge variant="success">承認済み</x-badges.badge>
<x-badges.badge variant="warning">承認待ち</x-badges.badge>
<x-badges.badge variant="danger">却下</x-badges.badge>
<x-badges.badge variant="default">下書き</x-badges.badge>
```

### テーブル操作ボタン
```blade
<x-buttons.table-action variant="edit" :href="route('xxx.edit', $model, false)">編集</x-buttons.table-action>
<form method="POST" action="{{ route('xxx.destroy', $model) }}">
    @csrf @method('DELETE')
    <x-buttons.table-action variant="delete">削除</x-buttons.table-action>
</form>
```

### 検索フォーム
```blade
<form method="GET" action="{{ route('xxx.index') }}" class="flex flex-wrap gap-2 items-end">
    <x-inputs.text-input type="text" name="search" value="{{ request('search') }}" placeholder="検索" />
    <x-buttons.search-button>検索</x-buttons.search-button>
    @if(request('search'))
        <x-buttons.link-button variant="ghost" :href="route('xxx.index')">クリア</x-buttons.link-button>
    @endif
</form>
```
