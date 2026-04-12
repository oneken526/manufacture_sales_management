<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * ロール別初期ユーザーを作成する
     * 本番環境では必ずパスワードを変更すること
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@example.com',
                'name'  => '管理者',
                'role'  => 'admin',
            ],
            [
                'email' => 'sales@example.com',
                'name'  => '営業担当',
                'role'  => 'sales',
            ],
            [
                'email' => 'manufacture@example.com',
                'name'  => '製造担当',
                'role'  => 'manufacture',
            ],
            [
                'email' => 'warehouse@example.com',
                'name'  => '倉庫担当',
                'role'  => 'warehouse',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->syncRoles([$data['role']]);
        }
    }
}
