# css-unify テストケース一覧

`/css-unify` コマンドの全問題タイプを検出できることを確認するために  
意図的に埋め込んだ CSS 問題の一覧。

---

## 問題A — 1ファイルのみ、app.css と 80% 以上一致

| クラス名 | ファイル | 比較対象 (app.css) | 一致率 |
|---|---|---|---|
| `.wh-search-input` | `warehouses/index.blade.php` | `.search-input` | 5/6 = 83% |

**差異:** `width: 288px` が wh-search-input に存在しない

---

## 問題B ケース1 — 同名クラスが複数ファイルに重複（app.css 未定義）

| クラス名 | ファイル | ファイル数 | 一致率 |
|---|---|---|---|
| `.submit-btn` | products/index, products/create, products/edit | 3 | ~100% |
| `.action-btn` | users/index, users/create, users/edit | 3 | ~100% |

---

## 問題B ケース2 — 異なる名前、複数ファイル、app.css に対応なし（50% 未満）

| クラス名 | ファイル | ローカル同士の一致率 | 比較対象 (app.css) | app.css との一致率 |
|---|---|---|---|---|
| `.danger-block` | `products/show.blade.php` | 100% | `.danger-zone` | 1/6 = 17% |
| `.warn-box` | `users/index.blade.php` | 100% | `.danger-zone` | 1/6 = 17% |

**共通スタイル:**
```css
background: #fff;
border: 1px solid #fecaca;
border-radius: 0.5rem;
padding: 1.25rem 1.5rem;
```

---

## 問題B ケース3 — 異なる名前、複数ファイル、app.css と 50〜79% 一致（⚠️ 要確認）

| クラス名 | ファイル | ローカル同士の一致率 | 比較対象 (app.css) | app.css との一致率 |
|---|---|---|---|---|
| `.prd-header` | `products/index.blade.php` | 100% | `.page-header` | 3/4 = 75% |
| `.user-page-header` | `users/index.blade.php` | 100% | `.page-header` | 3/4 = 75% |
| `.cust-page-header` | `customers/index.blade.php` | 100% | `.page-header` | 3/4 = 75% |

**差異:** `margin-bottom` の値が異なる（ローカル: `1.5rem` / app.css: `24px`）

---

## 命名衝突 — 同名クラスが複数ファイルに存在するが 80% 未満一致

| クラス名 | ファイル | プロパティ | 一致率 |
|---|---|---|---|
| `.list-footer` | `products/index.blade.php` | `display: flex; justify-content: flex-end;`（2 props） | 1/6 = 17% |
| `.list-footer` | `users/index.blade.php` | `background: #f8fafc; padding: 0.75rem; border-radius: 0.375rem; display: flex; align-items: center; gap: 1rem;`（6 props） | 1/6 = 17% |

---

## 問題C — app.css にあるが未使用（全ファイルスキャン時のみ）

引数指定スキャンではスキップされる仕様のため、専用テストケースなし。  
全ファイルスキャン（引数なし）で実行した際に自動検出される。
