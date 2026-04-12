<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * シーダーを実行する
     * 実行順序: RoleSeeder → AdminUserSeeder（依存関係あり）
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            CustomerSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            WarehouseSeeder::class,
        ]);
    }
}
