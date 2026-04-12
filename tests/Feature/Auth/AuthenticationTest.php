<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    #[Test]
    public function ログイン画面が表示される(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('製造業販売管理システム');
        $response->assertSee('ログイン');
    }

    #[Test]
    public function adminユーザーがログインするとダッシュボードへリダイレクトされる(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $user->assignRole('admin');

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    #[Test]
    public function salesユーザーがログインするとダッシュボードへリダイレクトされる(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $user->assignRole('sales');

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    #[Test]
    public function manufactureユーザーがログインするとmanufactureOrdersへリダイレクトされる(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $user->assignRole('manufacture');

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/manufacture-orders');
    }

    #[Test]
    public function warehouseユーザーがログインするとshipmentsへリダイレクトされる(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $user->assignRole('warehouse');

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/shipments');
    }

    #[Test]
    public function 無効な認証情報でエラーメッセージが返される(): void
    {
        $response = $this->post('/login', [
            'email'    => 'notexist@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
    }

    #[Test]
    public function ログアウト後にloginへリダイレクトされる(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
    }
}
