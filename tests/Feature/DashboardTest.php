<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[Test]
    public function 未認証アクセスはloginへリダイレクトされる(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    #[Test]
    public function adminユーザーがダッシュボードにアクセスできる(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    #[Test]
    public function salesユーザーがダッシュボードにアクセスできる(): void
    {
        $user = User::factory()->create();
        $user->assignRole('sales');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    #[Test]
    public function manufactureユーザーがダッシュボードにアクセスできる(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manufacture');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    #[Test]
    public function warehouseユーザーがダッシュボードにアクセスできる(): void
    {
        $user = User::factory()->create();
        $user->assignRole('warehouse');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    #[Test]
    public function salesロールは製造指示メニューが表示されない(): void
    {
        $user = User::factory()->create();
        $user->assignRole('sales');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertDontSee('製造指示');
    }

    #[Test]
    public function adminロールは全メニューが表示される(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('得意先管理');
        $response->assertSee('商品管理');
        $response->assertSee('製造指示');
        $response->assertSee('在庫管理');
        $response->assertSee('請求管理');
    }

    #[Test]
    public function warehouseロールは販売管理メニューが表示されない(): void
    {
        $user = User::factory()->create();
        $user->assignRole('warehouse');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertDontSee('見積管理');
        $response->assertDontSee('受注管理');
        $response->assertSee('出荷管理');
        $response->assertSee('在庫管理');
    }
}
