<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * 4ロールを作成する
     * - admin: システム全体の管理（マスタ管理・ユーザー管理含む）
     * - sales: 見積・受注・得意先管理・レポート
     * - manufacture: 製造指示の参照・ステータス更新
     * - warehouse: 在庫管理・出荷処理
     */
    public function run(): void
    {
        $roles = ['admin', 'sales', 'manufacture', 'warehouse'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }
}
