<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerSpecialPrice;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustomerSpecialPriceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $sales;
    private User $warehouse;
    private Customer $customer;
    private Product $product;
    private ProductCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->sales = User::factory()->create();
        $this->sales->assignRole('sales');

        $this->warehouse = User::factory()->create();
        $this->warehouse->assignRole('warehouse');

        $this->category = ProductCategory::create(['name' => 'テストカテゴリ']);

        $this->customer = Customer::create([
            'code'         => 'C001',
            'name'         => 'テスト得意先',
            'closing_day'  => 99,
            'credit_limit' => 0,
        ]);

        $this->product = Product::create([
            'code'        => 'P001',
            'name'        => 'テスト商品',
            'category_id' => $this->category->id,
            'unit_price'  => 1000,
        ]);
    }

    // =========================================================
    // アクセス制御
    // =========================================================

    #[Test]
    public function 未認証アクセスはloginへリダイレクトされる(): void
    {
        // 【テスト目的】: 未認証ユーザーが特別単価一覧にアクセスできないことを確認
        // 【期待される動作】: /login へリダイレクト
        // 🔵 信頼性レベル: ルート設定 auth ミドルウェアから確実

        $this->get("/customers/{$this->customer->id}/special-prices")
            ->assertRedirect('/login');
    }

    #[Test]
    public function warehouseロールは特別単価管理にアクセスできない(): void
    {
        // 【テスト目的】: warehouse ロールが特別単価管理にアクセス不可なことを確認
        // 【期待される動作】: 403 Forbidden
        // 🔵 信頼性レベル: タスク仕様書 role:admin|sales ミドルウェアより

        $this->actingAs($this->warehouse)
            ->get("/customers/{$this->customer->id}/special-prices")
            ->assertStatus(403);
    }

    #[Test]
    public function adminが得意先の特別単価一覧を表示できる(): void
    {
        // 【テスト目的】: admin ロールが特別単価一覧を正常に表示できることを確認
        // 【期待される動作】: 200 OK、得意先名が表示される
        // 🔵 信頼性レベル: タスク完了条件「GET /customers/{id}/special-prices」より

        CustomerSpecialPrice::create([
            'customer_id' => $this->customer->id,
            'product_id'  => $this->product->id,
            'unit_price'  => 800,
        ]);

        $this->actingAs($this->admin)
            ->get("/customers/{$this->customer->id}/special-prices")
            ->assertStatus(200)
            ->assertSee('テスト得意先')  // 【確認内容】: 得意先名が表示される
            ->assertSee('テスト商品');   // 【確認内容】: 商品名が表示される
    }

    #[Test]
    public function salesが得意先の特別単価一覧を表示できる(): void
    {
        // 【テスト目的】: sales ロールも特別単価一覧を閲覧できることを確認
        // 【期待される動作】: 200 OK
        // 🔵 信頼性レベル: タスク仕様書 role:admin|sales より

        $this->actingAs($this->sales)
            ->get("/customers/{$this->customer->id}/special-prices")
            ->assertStatus(200);
    }

    // =========================================================
    // 特別単価の登録
    // =========================================================

    #[Test]
    public function adminが特別単価を新規登録できる(): void
    {
        // 【テスト目的】: POST で特別単価を登録できることを確認
        // 【期待される動作】: DB に保存され /customers/{id}/special-prices へリダイレクト
        // 🔵 信頼性レベル: タスク完了条件「POST /customers/{id}/special-prices」より

        $this->actingAs($this->admin)
            ->post("/customers/{$this->customer->id}/special-prices", [
                'product_id' => $this->product->id,
                'unit_price' => 800,
            ])
            ->assertRedirect("/customers/{$this->customer->id}/special-prices");

        $this->assertDatabaseHas('customer_special_prices', [
            'customer_id' => $this->customer->id,
            'product_id'  => $this->product->id,
            'unit_price'  => 800,
        ]); // 【確認内容】: 特別単価がDBに保存されている
    }

    #[Test]
    public function salesが特別単価を登録できる(): void
    {
        // 【テスト目的】: sales ロールも特別単価を登録できることを確認
        // 【期待される動作】: DB に保存される
        // 🔵 信頼性レベル: タスク仕様書 role:admin|sales より

        $this->actingAs($this->sales)
            ->post("/customers/{$this->customer->id}/special-prices", [
                'product_id' => $this->product->id,
                'unit_price' => 900,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('customer_special_prices', [
            'customer_id' => $this->customer->id,
            'product_id'  => $this->product->id,
        ]); // 【確認内容】: salesロールでも登録できる
    }

    #[Test]
    public function 同一得意先同一商品の特別単価重複登録はバリデーションエラー(): void
    {
        // 【テスト目的】: 同じ得意先・商品の組み合わせで重複登録できないことを確認
        // 【期待される動作】: product_id のバリデーションエラー
        // 🔵 信頼性レベル: タスク完了条件「重複登録されるとバリデーションエラー」より

        CustomerSpecialPrice::create([
            'customer_id' => $this->customer->id,
            'product_id'  => $this->product->id,
            'unit_price'  => 800,
        ]);

        $this->actingAs($this->admin)
            ->post("/customers/{$this->customer->id}/special-prices", [
                'product_id' => $this->product->id,
                'unit_price' => 700,
            ])
            ->assertSessionHasErrors('product_id'); // 【確認内容】: 重複エラーが発生する
    }

    #[Test]
    public function unit_priceが0未満の場合はバリデーションエラー(): void
    {
        // 【テスト目的】: 負の単価が登録できないことを確認
        // 【期待される動作】: unit_price のバリデーションエラー
        // 🔵 信頼性レベル: タスク仕様書 store バリデーション min:0 より

        $this->actingAs($this->admin)
            ->post("/customers/{$this->customer->id}/special-prices", [
                'product_id' => $this->product->id,
                'unit_price' => -1,
            ])
            ->assertSessionHasErrors('unit_price'); // 【確認内容】: 負の値はエラー
    }

    #[Test]
    public function 存在しないproduct_idはバリデーションエラー(): void
    {
        // 【テスト目的】: 存在しない商品IDで登録できないことを確認
        // 【期待される動作】: product_id のバリデーションエラー
        // 🔵 信頼性レベル: タスク仕様書 exists:products,id バリデーションより

        $this->actingAs($this->admin)
            ->post("/customers/{$this->customer->id}/special-prices", [
                'product_id' => 99999,
                'unit_price' => 800,
            ])
            ->assertSessionHasErrors('product_id'); // 【確認内容】: 存在しない商品IDはエラー
    }

    #[Test]
    public function unit_priceが必須である(): void
    {
        // 【テスト目的】: unit_price が空の場合にバリデーションエラーになることを確認
        // 【期待される動作】: unit_price のバリデーションエラー
        // 🔵 信頼性レベル: タスク仕様書 required バリデーションより

        $this->actingAs($this->admin)
            ->post("/customers/{$this->customer->id}/special-prices", [
                'product_id' => $this->product->id,
            ])
            ->assertSessionHasErrors('unit_price'); // 【確認内容】: unit_price は必須
    }

    // =========================================================
    // 特別単価の更新
    // =========================================================

    #[Test]
    public function 特別単価を更新できる(): void
    {
        // 【テスト目的】: PUT で既存の特別単価を更新できることを確認
        // 【期待される動作】: DB が更新され /customers/{id}/special-prices へリダイレクト
        // 🔵 信頼性レベル: タスク完了条件「PUT /customers/{id}/special-prices/{spId}」より

        $sp = CustomerSpecialPrice::create([
            'customer_id' => $this->customer->id,
            'product_id'  => $this->product->id,
            'unit_price'  => 800,
        ]);

        $this->actingAs($this->admin)
            ->put("/customers/{$this->customer->id}/special-prices/{$sp->id}", [
                'unit_price' => 750,
            ])
            ->assertRedirect("/customers/{$this->customer->id}/special-prices");

        $this->assertDatabaseHas('customer_special_prices', [
            'id'         => $sp->id,
            'unit_price' => 750,
        ]); // 【確認内容】: 単価が更新されている
    }

    // =========================================================
    // 特別単価の削除
    // =========================================================

    #[Test]
    public function 特別単価をソフトデリートできる(): void
    {
        // 【テスト目的】: DELETE で特別単価をソフトデリートできることを確認
        // 【期待される動作】: deleted_at が設定され論理削除される
        // 🔵 信頼性レベル: タスク完了条件「DELETE /customers/{id}/special-prices/{spId}」より

        $sp = CustomerSpecialPrice::create([
            'customer_id' => $this->customer->id,
            'product_id'  => $this->product->id,
            'unit_price'  => 800,
        ]);

        $this->actingAs($this->admin)
            ->delete("/customers/{$this->customer->id}/special-prices/{$sp->id}")
            ->assertRedirect("/customers/{$this->customer->id}/special-prices");

        $this->assertSoftDeleted('customer_special_prices', ['id' => $sp->id]); // 【確認内容】: ソフトデリートされている
    }

    // =========================================================
    // Ajax 単価取得エンドポイント
    // =========================================================

    #[Test]
    public function 特別単価ありのとき単価取得APIは特別単価を返す(): void
    {
        // 【テスト目的】: GET /api/customers/{id}/unit-price で特別単価が優先されることを確認
        // 【テスト内容】: 標準単価 1000、特別単価 800 を登録し API を呼び出す
        // 【期待される動作】: {"unit_price": 800, "is_special": true}
        // 🔵 信頼性レベル: タスク完了条件・テストケース1より

        CustomerSpecialPrice::create([
            'customer_id' => $this->customer->id,
            'product_id'  => $this->product->id,
            'unit_price'  => 800,
        ]);

        $this->actingAs($this->sales)
            ->getJson("/api/customers/{$this->customer->id}/unit-price?product_id={$this->product->id}")
            ->assertStatus(200)
            ->assertJson([
                'unit_price' => 800,
                'is_special' => true,
            ]); // 【確認内容】: 特別単価が返り is_special=true
    }

    #[Test]
    public function 特別単価なしのとき単価取得APIは標準単価を返す(): void
    {
        // 【テスト目的】: 特別単価未登録時に商品の標準単価が返ることを確認
        // 【テスト内容】: 特別単価を登録せずに API を呼び出す
        // 【期待される動作】: {"unit_price": 1000, "is_special": false}
        // 🔵 信頼性レベル: タスク完了条件・テストケース2より

        $this->actingAs($this->sales)
            ->getJson("/api/customers/{$this->customer->id}/unit-price?product_id={$this->product->id}")
            ->assertStatus(200)
            ->assertJson([
                'unit_price' => 1000,
                'is_special' => false,
            ]); // 【確認内容】: 標準単価が返り is_special=false
    }

    #[Test]
    public function 単価取得APIは未認証アクセスをリジェクトする(): void
    {
        // 【テスト目的】: 未認証ユーザーが単価取得 API を呼び出せないことを確認
        // 【期待される動作】: 401 または /login へリダイレクト
        // 🟡 信頼性レベル: auth ミドルウェア適用を想定（仕様書に明示なし）

        $this->getJson("/api/customers/{$this->customer->id}/unit-price?product_id={$this->product->id}")
            ->assertStatus(401); // 【確認内容】: 未認証は弾かれる
    }
}
