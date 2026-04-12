<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WarehouseTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $sales;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->sales = User::factory()->create();
        $this->sales->assignRole('sales');
    }

    #[Test]
    public function 未認証アクセスはloginへリダイレクトされる(): void
    {
        $this->get('/warehouses')->assertRedirect('/login');
    }

    #[Test]
    public function salesロールは倉庫管理にアクセスできない(): void
    {
        $this->actingAs($this->sales)
            ->get('/warehouses')
            ->assertStatus(403);
    }

    #[Test]
    public function adminが倉庫一覧を表示できる(): void
    {
        Warehouse::factory()->create(['code' => 'W001', 'name' => '第1倉庫']);

        $this->actingAs($this->admin)
            ->get('/warehouses')
            ->assertStatus(200)
            ->assertSee('第1倉庫');
    }

    #[Test]
    public function adminが倉庫を新規登録できる(): void
    {
        $this->actingAs($this->admin)
            ->post('/warehouses', ['code' => 'W001', 'name' => '第1倉庫'])
            ->assertRedirect('/warehouses');

        $this->assertDatabaseHas('warehouses', ['code' => 'W001', 'name' => '第1倉庫']);
    }

    #[Test]
    public function コードが重複している場合はバリデーションエラーになる(): void
    {
        Warehouse::factory()->create(['code' => 'W001', 'name' => '既存倉庫']);

        $this->actingAs($this->admin)
            ->post('/warehouses', ['code' => 'W001', 'name' => '新規倉庫'])
            ->assertSessionHasErrors('code');
    }

    #[Test]
    public function コードが未入力の場合はバリデーションエラーになる(): void
    {
        $this->actingAs($this->admin)
            ->post('/warehouses', ['code' => '', 'name' => '倉庫名'])
            ->assertSessionHasErrors('code');
    }

    #[Test]
    public function 倉庫名が未入力の場合はバリデーションエラーになる(): void
    {
        $this->actingAs($this->admin)
            ->post('/warehouses', ['code' => 'W001', 'name' => ''])
            ->assertSessionHasErrors('name');
    }

    #[Test]
    public function adminが倉庫を更新できる(): void
    {
        $warehouse = Warehouse::factory()->create(['code' => 'W001', 'name' => '旧倉庫名']);

        $this->actingAs($this->admin)
            ->put("/warehouses/{$warehouse->id}", ['code' => 'W001', 'name' => '新倉庫名'])
            ->assertRedirect('/warehouses');

        $this->assertDatabaseHas('warehouses', ['id' => $warehouse->id, 'name' => '新倉庫名']);
    }

    #[Test]
    public function 自分自身のコードは更新時に重複エラーにならない(): void
    {
        $warehouse = Warehouse::factory()->create(['code' => 'W001', 'name' => '第1倉庫']);

        $this->actingAs($this->admin)
            ->put("/warehouses/{$warehouse->id}", ['code' => 'W001', 'name' => '第1倉庫（更新）'])
            ->assertRedirect('/warehouses');

        $this->assertDatabaseHas('warehouses', ['id' => $warehouse->id, 'name' => '第1倉庫（更新）']);
    }

    #[Test]
    public function adminが倉庫をソフトデリートできる(): void
    {
        $warehouse = Warehouse::factory()->create(['code' => 'W001', 'name' => '第1倉庫']);

        $this->actingAs($this->admin)
            ->delete("/warehouses/{$warehouse->id}")
            ->assertRedirect('/warehouses');

        $this->assertSoftDeleted('warehouses', ['id' => $warehouse->id]);
    }
}
