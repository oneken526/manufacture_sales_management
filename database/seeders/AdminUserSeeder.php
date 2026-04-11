<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * 初期管理者ユーザーを作成する
     * 本番環境では必ずパスワードを変更すること
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => '管理者',
                'password' => Hash::make('password'),
            ]
        );

        // admin ロールを付与（Spatie Permission）
        $admin->assignRole('admin');
    }
}
