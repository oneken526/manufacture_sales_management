<?php

declare(strict_types=1);

namespace Tests\Feature\Quotation;

use App\Models\Customer;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * 見積書一覧画面のフィーチャーテスト
 *
 * 仕様書: docs/spec/manufacturing-sales-mgmt/quotation-management/quotation-list.md
 */
class QuotationIndexTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $sales;
    private User $manufacture;
    private User $warehouse;
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->admin     = User::factory()->create()->assignRole('admin');
        $this->sales     = User::factory()->create()->assignRole('sales');
        $this->manufacture = User::factory()->create()->assignRole('manufacture');
        $this->warehouse = User::factory()->create()->assignRole('warehouse');
        $this->customer  = Customer::factory()->create(['name' => 'テスト得意先']);
    }

    // =========================================================
    // アクセス制御
    // 仕様書: § 2. アクセスできるユーザー
    // =========================================================

    #[Test]
    public function 未認証ユーザーはloginにリダイレクトされる(): void
    {
        $this->get('/quotations')
            ->assertRedirect('/login');
    }

    #[Test]
    public function adminは見積書一覧にアクセスできる(): void
    {
        $this->actingAs($this->admin)
            ->get('/quotations')
            ->assertStatus(200);
    }

    #[Test]
    public function salesは見積書一覧にアクセスできる(): void
    {
        $this->actingAs($this->sales)
            ->get('/quotations')
            ->assertStatus(200);
    }

    #[Test]
    public function manufactureは見積書一覧にアクセスできない(): void
    {
        $this->actingAs($this->manufacture)
            ->get('/quotations')
            ->assertStatus(403);
    }

    #[Test]
    public function warehouseは見積書一覧にアクセスできない(): void
    {
        $this->actingAs($this->warehouse)
            ->get('/quotations')
            ->assertStatus(403);
    }

    // =========================================================
    // 表示項目
    // 仕様書: § 3. 一覧の表示項目
    // =========================================================

    #[Test]
    public function 見積書の各項目が一覧に表示される(): void
    {
        $quotation = Quotation::factory()->create([
            'quotation_number' => 'Q202605-001',
            'customer_id'      => $this->customer->id,
            'user_id'          => $this->sales->id,
            'valid_until'      => '2026-06-30',
            'status'           => 'draft',
        ]);

        $this->actingAs($this->admin)
            ->get('/quotations')
            ->assertStatus(200)
            ->assertSee('Q202605-001')       // 見積番号
            ->assertSee('テスト得意先')       // 得意先名
            ->assertSee('2026-06-30')         // 有効期限
            ->assertSee($this->sales->name);  // 作成者
    }

    #[Test]
    public function 見積書一覧に新規登録ボタンが表示される(): void
    {
        $this->actingAs($this->sales)
            ->get('/quotations')
            ->assertStatus(200)
            ->assertSee('新規登録');
    }

    // =========================================================
    // ページネーション
    // 仕様書: § 3. 1ページあたり20件
    // =========================================================

    #[Test]
    public function 一覧は1ページあたり20件表示される(): void
    {
        Quotation::factory()->count(21)->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
        ]);

        $response = $this->actingAs($this->admin)->get('/quotations');

        // 1ページ目は20件のみ表示される（21件目は次ページ）
        $quotations = $response->viewData('quotations');
        $this->assertCount(20, $quotations);
    }

    #[Test]
    public function ページネーションの2ページ目に残りの件数が表示される(): void
    {
        Quotation::factory()->count(21)->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
        ]);

        $quotations = $this->actingAs($this->admin)
            ->get('/quotations?page=2')
            ->viewData('quotations');

        $this->assertCount(1, $quotations);
    }

    // =========================================================
    // デフォルトソート
    // 仕様書: § 3. デフォルトは作成日の降順
    // =========================================================

    #[Test]
    public function 一覧は作成日の降順で表示される(): void
    {
        $older = Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'created_at'  => now()->subDays(2),
        ]);
        $newer = Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'created_at'  => now(),
        ]);

        $quotations = $this->actingAs($this->admin)
            ->get('/quotations')
            ->viewData('quotations');

        $this->assertEquals($newer->id, $quotations->first()->id);
        $this->assertEquals($older->id, $quotations->last()->id);
    }

    // =========================================================
    // 論理削除
    // 仕様書: § 3. 削除済みは表示されない
    // =========================================================

    #[Test]
    public function 削除済みの見積書は一覧に表示されない(): void
    {
        Quotation::factory()->create([
            'quotation_number' => 'Q202605-001',
            'customer_id'      => $this->customer->id,
            'user_id'          => $this->sales->id,
            'deleted_at'       => now(),
        ]);

        $this->actingAs($this->admin)
            ->get('/quotations')
            ->assertStatus(200)
            ->assertDontSee('Q202605-001');
    }

    // =========================================================
    // 検索・絞り込み
    // 仕様書: § 4. 検索・絞り込み
    // =========================================================

    #[Test]
    public function 見積番号で部分一致検索できる(): void
    {
        Quotation::factory()->create([
            'quotation_number' => 'Q202605-001',
            'customer_id'      => $this->customer->id,
            'user_id'          => $this->sales->id,
        ]);
        Quotation::factory()->create([
            'quotation_number' => 'Q202604-001',
            'customer_id'      => $this->customer->id,
            'user_id'          => $this->sales->id,
        ]);

        $quotations = $this->actingAs($this->admin)
            ->get('/quotations?quotation_number=202605')
            ->viewData('quotations');

        $this->assertCount(1, $quotations);
        $this->assertEquals('Q202605-001', $quotations->first()->quotation_number);
    }

    #[Test]
    public function 得意先名で部分一致検索できる(): void
    {
        $other = Customer::factory()->create(['name' => '別の会社']);
        Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
        ]);
        Quotation::factory()->create([
            'customer_id' => $other->id,
            'user_id'     => $this->sales->id,
        ]);

        $quotations = $this->actingAs($this->admin)
            ->get('/quotations?customer_name=テスト')
            ->viewData('quotations');

        $this->assertCount(1, $quotations);
        $this->assertEquals($this->customer->id, $quotations->first()->customer_id);
    }

    #[Test]
    public function ステータスで完全一致検索できる(): void
    {
        Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'status'      => 'draft',
        ]);
        Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'status'      => 'approved',
        ]);

        $quotations = $this->actingAs($this->admin)
            ->get('/quotations?status=draft')
            ->viewData('quotations');

        $this->assertCount(1, $quotations);
        $this->assertEquals('draft', $quotations->first()->status);
    }

    #[Test]
    public function 作成日の開始日で絞り込みできる(): void
    {
        Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'created_at'  => '2026-05-10',
        ]);
        Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'created_at'  => '2026-05-20',
        ]);

        $quotations = $this->actingAs($this->admin)
            ->get('/quotations?created_from=2026-05-15')
            ->viewData('quotations');

        $this->assertCount(1, $quotations);
    }

    #[Test]
    public function 作成日の終了日で絞り込みできる(): void
    {
        Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'created_at'  => '2026-05-10',
        ]);
        Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'created_at'  => '2026-05-20',
        ]);

        $quotations = $this->actingAs($this->admin)
            ->get('/quotations?created_to=2026-05-15')
            ->viewData('quotations');

        $this->assertCount(1, $quotations);
    }

    #[Test]
    public function 複数条件はANDで絞り込まれる(): void
    {
        $other = Customer::factory()->create(['name' => '別の会社']);

        Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'status'      => 'draft',
        ]);
        Quotation::factory()->create([
            'customer_id' => $other->id,
            'user_id'     => $this->sales->id,
            'status'      => 'draft',
        ]);

        $quotations = $this->actingAs($this->admin)
            ->get('/quotations?customer_name=テスト&status=draft')
            ->viewData('quotations');

        $this->assertCount(1, $quotations);
        $this->assertEquals($this->customer->id, $quotations->first()->customer_id);
    }

    // =========================================================
    // 操作ボタンの表示制御
    // 仕様書: § 6. 操作ボタン
    // =========================================================

    #[Test]
    public function draft状態の見積書には編集ボタンが表示される(): void
    {
        $quotation = Quotation::factory()->create([
            'quotation_number' => 'Q202605-001',
            'customer_id'      => $this->customer->id,
            'user_id'          => $this->sales->id,
            'status'           => 'draft',
        ]);

        $this->actingAs($this->sales)
            ->get('/quotations')
            ->assertSee("href=\"/quotations/{$quotation->id}/edit\"", false);
    }

    #[Test]
    public function pending状態の見積書には編集ボタンが表示されない(): void
    {
        $quotation = Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'status'      => 'pending',
        ]);

        $this->actingAs($this->sales)
            ->get('/quotations')
            ->assertDontSee("href=\"/quotations/{$quotation->id}/edit\"", false);
    }

    #[Test]
    public function approved状態の見積書には編集ボタンが表示されない(): void
    {
        $quotation = Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'status'      => 'approved',
        ]);

        $this->actingAs($this->sales)
            ->get('/quotations')
            ->assertDontSee("href=\"/quotations/{$quotation->id}/edit\"", false);
    }

    #[Test]
    public function rejected状態の見積書には編集ボタンが表示されない(): void
    {
        $quotation = Quotation::factory()->create([
            'customer_id' => $this->customer->id,
            'user_id'     => $this->sales->id,
            'status'      => 'rejected',
        ]);

        $this->actingAs($this->sales)
            ->get('/quotations')
            ->assertDontSee("href=\"/quotations/{$quotation->id}/edit\"", false);
    }

    #[Test]
    public function 詳細ボタンはすべてのステータスで表示される(): void
    {
        foreach (['draft', 'pending', 'approved', 'rejected'] as $status) {
            $quotation = Quotation::factory()->create([
                'customer_id' => $this->customer->id,
                'user_id'     => $this->sales->id,
                'status'      => $status,
            ]);

            $this->actingAs($this->sales)
                ->get('/quotations')
                ->assertSee("href=\"/quotations/{$quotation->id}\"", false);

            $quotation->forceDelete();
        }
    }

    #[Test]
    public function 複製ボタンはすべてのステータスで表示される(): void
    {
        foreach (['draft', 'pending', 'approved', 'rejected'] as $status) {
            $quotation = Quotation::factory()->create([
                'customer_id' => $this->customer->id,
                'user_id'     => $this->sales->id,
                'status'      => $status,
            ]);

            $this->actingAs($this->sales)
                ->get('/quotations')
                ->assertSee("quotations/{$quotation->id}/copy", false);

            $quotation->forceDelete();
        }
    }
}
