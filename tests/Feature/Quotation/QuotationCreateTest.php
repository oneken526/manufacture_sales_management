<?php

declare(strict_types=1);

namespace Tests\Feature\Quotation;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * 見積書登録画面のフィーチャーテスト
 *
 * 仕様書: docs/spec/manufacturing-sales-mgmt/feature-specs/quotation-management/quotation-create.md
 */
class QuotationCreateTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $sales;
    private User $manufacture;
    private User $warehouse;
    private Customer $customer;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->admin       = User::factory()->create()->assignRole('admin');
        $this->sales       = User::factory()->create()->assignRole('sales');
        $this->manufacture = User::factory()->create()->assignRole('manufacture');
        $this->warehouse   = User::factory()->create()->assignRole('warehouse');
        $this->customer    = Customer::factory()->create(['name' => 'テスト得意先']);
        $this->product     = Product::factory()->create(['unit_price' => 1000]);
    }

    // =========================================================
    // アクセス制御（登録フォーム表示）
    // 仕様書: § 2. アクセスできるユーザー
    // =========================================================

    #[Test]
    public function 未認証ユーザーはloginにリダイレクトされる(): void
    {
        $this->get('/quotations/create')
            ->assertRedirect('/login');
    }

    #[Test]
    public function adminは登録フォームにアクセスできる(): void
    {
        $this->actingAs($this->admin)
            ->get('/quotations/create')
            ->assertStatus(200);
    }

    #[Test]
    public function salesは登録フォームにアクセスできる(): void
    {
        $this->actingAs($this->sales)
            ->get('/quotations/create')
            ->assertStatus(200);
    }

    #[Test]
    public function manufactureは登録フォームにアクセスできない(): void
    {
        $this->actingAs($this->manufacture)
            ->get('/quotations/create')
            ->assertStatus(403);
    }

    #[Test]
    public function warehouseは登録フォームにアクセスできない(): void
    {
        $this->actingAs($this->warehouse)
            ->get('/quotations/create')
            ->assertStatus(403);
    }

    // =========================================================
    // フォーム表示
    // 仕様書: § 3. 画面構成 / § 4. 基本情報の入力項目
    // =========================================================

    #[Test]
    public function 登録フォームに得意先セレクトボックスが表示される(): void
    {
        $this->actingAs($this->sales)
            ->get('/quotations/create')
            ->assertStatus(200)
            ->assertSee('テスト得意先');
    }

    #[Test]
    public function 登録フォームにログインユーザー名が作成者として表示される(): void
    {
        $this->actingAs($this->sales)
            ->get('/quotations/create')
            ->assertStatus(200)
            ->assertSee($this->sales->name);
    }

    #[Test]
    public function 登録フォームに保存ボタンが表示される(): void
    {
        $this->actingAs($this->sales)
            ->get('/quotations/create')
            ->assertStatus(200)
            ->assertSee('保存する');
    }

    #[Test]
    public function 登録フォームに一覧へ戻るキャンセルリンクが表示される(): void
    {
        $this->actingAs($this->sales)
            ->get('/quotations/create')
            ->assertStatus(200)
            ->assertSee('href="/quotations"', false);
    }

    // =========================================================
    // 保存成功
    // 仕様書: § 9. 保存・キャンセル
    // =========================================================

    #[Test]
    public function 正常なデータで保存するとquotationsテーブルに1件追加される(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'valid_until' => '2026-06-30',
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 5,
                        'unit_price' => 1000,
                    ],
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('quotations', 1);
    }

    #[Test]
    public function 保存後の見積書はdraftステータスで作成される(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                        'unit_price' => 500,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('quotations', [
            'customer_id' => $this->customer->id,
            'status'      => 'draft',
        ]);
    }

    #[Test]
    public function 保存後の見積書の作成者はログインユーザーになる(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                        'unit_price' => 500,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('quotations', [
            'user_id' => $this->sales->id,
        ]);
    }

    #[Test]
    public function 保存後は詳細画面にリダイレクトされる(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                        'unit_price' => 500,
                    ],
                ],
            ])
            ->assertRedirect('/quotations/1');
    }

    #[Test]
    public function 保存時に明細がquotation_detailsテーブルに保存される(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 3,
                        'unit_price' => 2000,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('quotation_details', [
            'product_id' => $this->product->id,
            'quantity'   => 3,
            'unit_price' => 2000,
            'amount'     => 6000,
        ]);
    }

    // =========================================================
    // 見積番号の自動採番
    // 仕様書: § 4. 見積番号の採番形式
    // =========================================================

    #[Test]
    public function 見積番号がQYYYYMM_3桁連番の形式で採番される(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                        'unit_price' => 1000,
                    ],
                ],
            ]);

        $expected = 'Q' . now()->format('Ym') . '-001';
        $this->assertDatabaseHas('quotations', ['quotation_number' => $expected]);
    }

    #[Test]
    public function 同月2件目の見積番号は連番が増える(): void
    {
        Quotation::factory()->create([
            'quotation_number' => 'Q' . now()->format('Ym') . '-001',
            'customer_id'      => $this->customer->id,
            'user_id'          => $this->sales->id,
        ]);

        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                        'unit_price' => 1000,
                    ],
                ],
            ]);

        $expected = 'Q' . now()->format('Ym') . '-002';
        $this->assertDatabaseHas('quotations', ['quotation_number' => $expected]);
    }

    // =========================================================
    // バリデーション
    // 仕様書: § 10. バリデーション
    // =========================================================

    #[Test]
    public function 得意先が未選択の場合はバリデーションエラーになる(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => null,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                        'unit_price' => 1000,
                    ],
                ],
            ])
            ->assertSessionHasErrors('customer_id');
    }

    #[Test]
    public function 明細が0行の場合はバリデーションエラーになる(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details'     => [],
            ])
            ->assertSessionHasErrors('details');
    }

    #[Test]
    public function 明細の商品が未選択の場合はバリデーションエラーになる(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details' => [
                    [
                        'product_id' => null,
                        'quantity'   => 1,
                        'unit_price' => 1000,
                    ],
                ],
            ])
            ->assertSessionHasErrors('details.0.product_id');
    }

    #[Test]
    public function 数量が1未満の場合はバリデーションエラーになる(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 0,
                        'unit_price' => 1000,
                    ],
                ],
            ])
            ->assertSessionHasErrors('details.0.quantity');
    }

    #[Test]
    public function 単価が未入力の場合はバリデーションエラーになる(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                        'unit_price' => null,
                    ],
                ],
            ])
            ->assertSessionHasErrors('details.0.unit_price');
    }

    #[Test]
    public function 有効期限が不正な日付形式の場合はバリデーションエラーになる(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'valid_until' => 'not-a-date',
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                        'unit_price' => 1000,
                    ],
                ],
            ])
            ->assertSessionHasErrors('valid_until');
    }

    #[Test]
    public function 有効期限が未入力でも保存できる(): void
    {
        $this->actingAs($this->sales)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'valid_until' => null,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                        'unit_price' => 1000,
                    ],
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('quotations', 1);
        $this->assertDatabaseHas('quotations', ['valid_until' => null]);
    }

    // =========================================================
    // アクセス制御（POST）
    // 仕様書: § 2. アクセスできるユーザー
    // =========================================================

    #[Test]
    public function 未認証ユーザーのPOSTはloginにリダイレクトされる(): void
    {
        $this->post('/quotations', [
            'customer_id' => $this->customer->id,
            'details' => [
                [
                    'product_id' => $this->product->id,
                    'quantity'   => 1,
                    'unit_price' => 1000,
                ],
            ],
        ])->assertRedirect('/login');
    }

    #[Test]
    public function manufactureはPOSTで403になる(): void
    {
        $this->actingAs($this->manufacture)
            ->post('/quotations', [
                'customer_id' => $this->customer->id,
                'details' => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                        'unit_price' => 1000,
                    ],
                ],
            ])
            ->assertStatus(403);
    }
}
