<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * 倉庫サンプルデータを投入する
     */
    public function run(): void
    {
        $warehouses = [
            [
                'code' => 'WH-001',
                'name' => '第1倉庫（完成品）',
            ],
            [
                'code' => 'WH-002',
                'name' => '第2倉庫（原材料）',
            ],
            [
                'code' => 'WH-003',
                'name' => '第3倉庫（仕掛品）',
            ],
            [
                'code' => 'WH-004',
                'name' => '出荷待ちエリア',
            ],
        ];

        foreach ($warehouses as $data) {
            Warehouse::firstOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
