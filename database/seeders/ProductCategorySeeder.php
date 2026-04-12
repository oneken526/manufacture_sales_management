<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * 商品カテゴリサンプルデータを投入する
     * 親カテゴリ → 子カテゴリの2階層構造
     */
    public function run(): void
    {
        // 親カテゴリ
        $parents = [
            ['name' => '機械部品',     'sort_order' => 1],
            ['name' => '電子部品',     'sort_order' => 2],
            ['name' => '素材・材料',   'sort_order' => 3],
            ['name' => '工具・消耗品', 'sort_order' => 4],
            ['name' => '外注加工品',   'sort_order' => 5],
        ];

        foreach ($parents as $data) {
            ProductCategory::firstOrCreate(
                ['name' => $data['name'], 'parent_id' => null],
                $data
            );
        }

        // 子カテゴリ
        $children = [
            // 機械部品
            ['name' => 'ボルト・ナット',    'parent' => '機械部品',   'sort_order' => 1],
            ['name' => 'ベアリング',        'parent' => '機械部品',   'sort_order' => 2],
            ['name' => 'シール・ガスケット', 'parent' => '機械部品',   'sort_order' => 3],
            ['name' => 'スプリング・バネ',  'parent' => '機械部品',   'sort_order' => 4],
            ['name' => 'チェーン・スプロケット', 'parent' => '機械部品', 'sort_order' => 5],
            // 電子部品
            ['name' => '基板・PCB',         'parent' => '電子部品',   'sort_order' => 1],
            ['name' => 'センサー類',        'parent' => '電子部品',   'sort_order' => 2],
            ['name' => 'コネクタ・端子',    'parent' => '電子部品',   'sort_order' => 3],
            ['name' => 'リレー・スイッチ',  'parent' => '電子部品',   'sort_order' => 4],
            // 素材・材料
            ['name' => '金属材料',          'parent' => '素材・材料', 'sort_order' => 1],
            ['name' => '樹脂材料',          'parent' => '素材・材料', 'sort_order' => 2],
            ['name' => 'ゴム・パッキン',    'parent' => '素材・材料', 'sort_order' => 3],
            // 工具・消耗品
            ['name' => '切削工具',          'parent' => '工具・消耗品', 'sort_order' => 1],
            ['name' => '研削砥石',          'parent' => '工具・消耗品', 'sort_order' => 2],
            ['name' => '測定工具',          'parent' => '工具・消耗品', 'sort_order' => 3],
            // 外注加工品
            ['name' => '切削加工品',        'parent' => '外注加工品', 'sort_order' => 1],
            ['name' => '板金加工品',        'parent' => '外注加工品', 'sort_order' => 2],
            ['name' => '表面処理品',        'parent' => '外注加工品', 'sort_order' => 3],
        ];

        foreach ($children as $data) {
            $parent = ProductCategory::where('name', $data['parent'])->whereNull('parent_id')->first();
            if ($parent) {
                ProductCategory::firstOrCreate(
                    ['name' => $data['name'], 'parent_id' => $parent->id],
                    ['name' => $data['name'], 'parent_id' => $parent->id, 'sort_order' => $data['sort_order']]
                );
            }
        }
    }
}
