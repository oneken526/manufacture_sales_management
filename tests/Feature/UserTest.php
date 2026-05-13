<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
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
        $this->get('/users')->assertRedirect('/login');
    }

    #[Test]
    public function salesロールはユーザー管理にアクセスできない(): void
    {
        $this->actingAs($this->sales)
            ->get('/users')
            ->assertStatus(403);
    }

    #[Test]
    public function adminがユーザー一覧を表示できる(): void
    {
        $this->actingAs($this->admin)
            ->get('/users')
            ->assertStatus(200)
            ->assertSee($this->admin->name);
    }

    #[Test]
    public function adminがユーザーを新規作成しロールを付与できる(): void
    {
        $this->actingAs($this->admin)
            ->post('/users', [
                'name'                  => '新規ユーザー',
                'email'                 => 'new@example.com',
                'password'              => 'password123',
                'password_confirmation' => 'password123',
                'role'                  => 'sales',
            ])
            ->assertRedirect('/users');

        $user = User::where('email', 'new@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('sales'));
    }

    #[Test]
    public function 自身のアカウントは削除できない(): void
    {
        $this->actingAs($this->admin)
            ->delete("/users/{$this->admin->id}")
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $this->admin->id, 'deleted_at' => null]);
    }

    #[Test]
    public function adminが他ユーザーをソフトデリートできる(): void
    {
        $target = User::factory()->create();
        $target->assignRole('sales');

        $this->actingAs($this->admin)
            ->delete("/users/{$target->id}")
            ->assertRedirect('/users');

        $this->assertSoftDeleted('users', ['id' => $target->id]);
    }

    #[Test]
    public function adminがユーザー情報とロールを更新できる(): void
    {
        $target = User::factory()->create(['name' => '旧名前']);
        $target->assignRole('sales');

        $this->actingAs($this->admin)
            ->put("/users/{$target->id}", [
                'name'  => '新名前',
                'email' => $target->email,
                'role'  => 'manufacture',
            ])
            ->assertRedirect('/users');

        $this->assertDatabaseHas('users', ['id' => $target->id, 'name' => '新名前']);
        $this->assertTrue($target->fresh()->hasRole('manufacture'));
    }

    #[Test]
    public function メールアドレスが重複している場合はバリデーションエラーになる(): void
    {
        $this->actingAs($this->admin)
            ->post('/users', [
                'name'                  => '重複ユーザー',
                'email'                 => $this->sales->email,
                'password'              => 'password123',
                'password_confirmation' => 'password123',
                'role'                  => 'sales',
            ])
            ->assertSessionHasErrors('email');
    }

    #[Test]
    public function パスワードが8文字未満の場合はバリデーションエラーになる(): void
    {
        $this->actingAs($this->admin)
            ->post('/users', [
                'name'                  => 'テストユーザー',
                'email'                 => 'test@example.com',
                'password'              => 'short',
                'password_confirmation' => 'short',
                'role'                  => 'sales',
            ])
            ->assertSessionHasErrors('password');
    }

    #[Test]
    public function 不正なロールはバリデーションエラーになる(): void
    {
        $this->actingAs($this->admin)
            ->post('/users', [
                'name'                  => 'テストユーザー',
                'email'                 => 'test@example.com',
                'password'              => 'password123',
                'password_confirmation' => 'password123',
                'role'                  => 'invalid_role',
            ])
            ->assertSessionHasErrors('role');
    }
}
