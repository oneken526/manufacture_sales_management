# Red フェーズ記録: customer-special-price

## 作成テストケース一覧

| # | テストメソッド名 | 信頼性 | 失敗理由 |
|---|--------------|--------|---------|
| 1 | 未認証アクセスはloginへリダイレクトされる | 🔵 | ルート未定義（404） |
| 2 | warehouseロールは特別単価管理にアクセスできない | 🔵 | ルート未定義（404） |
| 3 | adminが得意先の特別単価一覧を表示できる | 🔵 | ルート未定義（404） |
| 4 | salesが得意先の特別単価一覧を表示できる | 🔵 | ルート未定義（404） |
| 5 | adminが特別単価を新規登録できる | 🔵 | ルート未定義（404） |
| 6 | salesが特別単価を登録できる | 🔵 | ルート未定義（404） |
| 7 | 同一得意先同一商品の特別単価重複登録はバリデーションエラー | 🔵 | ルート未定義（404） |
| 8 | unit_priceが0未満の場合はバリデーションエラー | 🔵 | ルート未定義（404） |
| 9 | 存在しないproduct_idはバリデーションエラー | 🔵 | ルート未定義（404） |
| 10 | unit_priceが必須である | 🔵 | ルート未定義（404） |
| 11 | 特別単価を更新できる | 🔵 | ルート未定義（404） |
| 12 | 特別単価をソフトデリートできる | 🔵 | ルート未定義（404） |
| 13 | 特別単価ありのとき単価取得APIは特別単価を返す | 🔵 | ルート未定義（404） |
| 14 | 特別単価なしのとき単価取得APIは標準単価を返す | 🔵 | ルート未定義（404） |
| 15 | 単価取得APIは未認証アクセスをリジェクトする | 🟡 | ルート未定義（404） |

**15件 全失敗 ✅**

## テストファイル

`tests/Feature/CustomerSpecialPriceTest.php`

## Green フェーズで実装すべき内容

1. **ルート追加** (`routes/web.php`)
   - `Route::resource('customers.special-prices', CustomerSpecialPriceController::class)->only(['index', 'store', 'update', 'destroy'])`
   - `Route::get('/api/customers/{customer}/unit-price', [Api\UnitPriceController::class, 'show'])`
   - middleware: `role:admin|sales`

2. **CustomerSpecialPriceController** (`app/Http/Controllers/CustomerSpecialPriceController.php`)
   - `index(Customer $customer)`: 特別単価一覧ビュー
   - `store(Request $request, Customer $customer)`: バリデーション + 登録
   - `update(Request $request, Customer $customer, CustomerSpecialPrice $specialPrice)`: 更新
   - `destroy(Customer $customer, CustomerSpecialPrice $specialPrice)`: ソフトデリート

3. **Api\UnitPriceController** (`app/Http/Controllers/Api/UnitPriceController.php`)
   - `show(Customer $customer, Request $request)`: 特別単価 or 標準単価を JSON 返却

4. **Blade ビュー** (`resources/views/customers/special-prices/index.blade.php`)
   - 特別単価一覧 + 登録フォーム + 編集・削除操作
