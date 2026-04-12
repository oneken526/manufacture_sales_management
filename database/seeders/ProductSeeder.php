<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * 商品サンプルデータを投入する
     */
    public function run(): void
    {
        // カテゴリ名 → ID のマップを作成
        $categoryMap = ProductCategory::pluck('id', 'name');

        $products = [
            // ボルト・ナット
            ['code' => 'P-0001', 'name' => '六角ボルト M6×15',       'category' => 'ボルト・ナット',    'unit_price' =>   10.00, 'unit_name' => '個', 'notes' => 'ステンレス製 SUS304'],
            ['code' => 'P-0002', 'name' => '六角ボルト M8×20',       'category' => 'ボルト・ナット',    'unit_price' =>   15.00, 'unit_name' => '個', 'notes' => 'ステンレス製 SUS304'],
            ['code' => 'P-0003', 'name' => '六角ボルト M10×30',      'category' => 'ボルト・ナット',    'unit_price' =>   25.00, 'unit_name' => '個', 'notes' => null],
            ['code' => 'P-0004', 'name' => '六角ナット M8',           'category' => 'ボルト・ナット',    'unit_price' =>    8.00, 'unit_name' => '個', 'notes' => null],
            ['code' => 'P-0005', 'name' => '六角ナット M10',          'category' => 'ボルト・ナット',    'unit_price' =>   12.00, 'unit_name' => '個', 'notes' => null],
            ['code' => 'P-0006', 'name' => '皿ボルト M6×12',         'category' => 'ボルト・ナット',    'unit_price' =>   12.00, 'unit_name' => '個', 'notes' => '鉄 三価クロメート'],

            // ベアリング
            ['code' => 'P-0011', 'name' => '深溝玉軸受 6202',        'category' => 'ベアリング',        'unit_price' =>  620.00, 'unit_name' => '個', 'notes' => '内径15mm 外径35mm'],
            ['code' => 'P-0012', 'name' => '深溝玉軸受 6204',        'category' => 'ベアリング',        'unit_price' =>  850.00, 'unit_name' => '個', 'notes' => '内径20mm 外径47mm'],
            ['code' => 'P-0013', 'name' => '深溝玉軸受 6205',        'category' => 'ベアリング',        'unit_price' =>  920.00, 'unit_name' => '個', 'notes' => '内径25mm 外径52mm'],
            ['code' => 'P-0014', 'name' => '深溝玉軸受 6208',        'category' => 'ベアリング',        'unit_price' => 1450.00, 'unit_name' => '個', 'notes' => '内径40mm 外径80mm'],
            ['code' => 'P-0015', 'name' => '円筒ころ軸受 NU205',     'category' => 'ベアリング',        'unit_price' => 2800.00, 'unit_name' => '個', 'notes' => null],

            // シール・ガスケット
            ['code' => 'P-0021', 'name' => 'オイルシール 20×35×7',  'category' => 'シール・ガスケット', 'unit_price' =>  380.00, 'unit_name' => '個', 'notes' => null],
            ['code' => 'P-0022', 'name' => 'Oリング P-20',           'category' => 'シール・ガスケット', 'unit_price' =>   45.00, 'unit_name' => '個', 'notes' => 'NBRゴム'],
            ['code' => 'P-0023', 'name' => 'フラットパッキン t3.0',  'category' => 'シール・ガスケット', 'unit_price' =>  120.00, 'unit_name' => '枚', 'notes' => 'ノンアスベスト'],

            // スプリング・バネ
            ['code' => 'P-0031', 'name' => '圧縮コイルバネ φ10×50', 'category' => 'スプリング・バネ',  'unit_price' =>  180.00, 'unit_name' => '個', 'notes' => 'SWC-B'],
            ['code' => 'P-0032', 'name' => '引張コイルバネ φ8×40',  'category' => 'スプリング・バネ',  'unit_price' =>  160.00, 'unit_name' => '個', 'notes' => null],

            // 基板・PCB
            ['code' => 'P-0041', 'name' => '制御基板 CB-100',        'category' => '基板・PCB',         'unit_price' => 12000.00, 'unit_name' => '枚', 'notes' => '自社製造品'],
            ['code' => 'P-0042', 'name' => '電源基板 PB-200',        'category' => '基板・PCB',         'unit_price' =>  8500.00, 'unit_name' => '枚', 'notes' => '入力DC24V'],
            ['code' => 'P-0043', 'name' => 'I/O拡張基板 IO-50',      'category' => '基板・PCB',         'unit_price' =>  5200.00, 'unit_name' => '枚', 'notes' => null],

            // センサー類
            ['code' => 'P-0051', 'name' => '温度センサー TS-200',    'category' => 'センサー類',        'unit_price' =>  3500.00, 'unit_name' => '個', 'notes' => '測定範囲: -40〜150℃'],
            ['code' => 'P-0052', 'name' => '圧力センサー PS-100',    'category' => 'センサー類',        'unit_price' =>  6800.00, 'unit_name' => '個', 'notes' => '測定範囲: 0〜1MPa'],
            ['code' => 'P-0053', 'name' => '近接センサー NS-10',     'category' => 'センサー類',        'unit_price' =>  2400.00, 'unit_name' => '個', 'notes' => 'DC12-24V'],
            ['code' => 'P-0054', 'name' => '光電センサー OS-300',    'category' => 'センサー類',        'unit_price' =>  4200.00, 'unit_name' => '個', 'notes' => null],

            // コネクタ・端子
            ['code' => 'P-0061', 'name' => '丸形コネクタ RC-8P',     'category' => 'コネクタ・端子',    'unit_price' =>   980.00, 'unit_name' => '個', 'notes' => '8ピン防水'],
            ['code' => 'P-0062', 'name' => '端子台 TB-12P',          'category' => 'コネクタ・端子',    'unit_price' =>  1200.00, 'unit_name' => '個', 'notes' => '12極 M3ネジ'],

            // 金属材料
            ['code' => 'P-0071', 'name' => 'アルミ板 A5052 t2.0',    'category' => '金属材料',          'unit_price' =>  3600.00, 'unit_name' => '枚', 'notes' => '1000×2000mm'],
            ['code' => 'P-0072', 'name' => 'アルミ板 A5052 t3.0',    'category' => '金属材料',          'unit_price' =>  4800.00, 'unit_name' => '枚', 'notes' => '1000×2000mm'],
            ['code' => 'P-0073', 'name' => 'SUS304 丸棒 φ20',        'category' => '金属材料',          'unit_price' =>  2200.00, 'unit_name' => '本', 'notes' => '長さ1000mm'],
            ['code' => 'P-0074', 'name' => 'SUS304 丸棒 φ30',        'category' => '金属材料',          'unit_price' =>  3800.00, 'unit_name' => '本', 'notes' => '長さ1000mm'],
            ['code' => 'P-0075', 'name' => '鉄板 SPCC t1.6',         'category' => '金属材料',          'unit_price' =>  1800.00, 'unit_name' => '枚', 'notes' => '1000×2000mm'],
            ['code' => 'P-0076', 'name' => 'アルミ角棒 20×20',       'category' => '金属材料',          'unit_price' =>  1500.00, 'unit_name' => '本', 'notes' => '長さ1000mm'],

            // 樹脂材料
            ['code' => 'P-0081', 'name' => 'MCナイロン板 t10',        'category' => '樹脂材料',          'unit_price' =>  5500.00, 'unit_name' => '枚', 'notes' => '300×300mm'],
            ['code' => 'P-0082', 'name' => 'ジュラコン丸棒 φ30',     'category' => '樹脂材料',          'unit_price' =>  2800.00, 'unit_name' => '本', 'notes' => '長さ500mm'],
            ['code' => 'P-0083', 'name' => 'アクリル板 t5.0',        'category' => '樹脂材料',          'unit_price' =>  2200.00, 'unit_name' => '枚', 'notes' => '透明 500×500mm'],

            // 切削工具
            ['code' => 'P-0091', 'name' => 'エンドミル φ6 4枚刃',    'category' => '切削工具',          'unit_price' =>  1200.00, 'unit_name' => '本', 'notes' => null],
            ['code' => 'P-0092', 'name' => 'エンドミル φ10 4枚刃',   'category' => '切削工具',          'unit_price' =>  1800.00, 'unit_name' => '本', 'notes' => null],
            ['code' => 'P-0093', 'name' => 'エンドミル φ16 4枚刃',   'category' => '切削工具',          'unit_price' =>  2600.00, 'unit_name' => '本', 'notes' => null],
            ['code' => 'P-0094', 'name' => 'ドリル φ8.0',            'category' => '切削工具',          'unit_price' =>   850.00, 'unit_name' => '本', 'notes' => 'HSS'],
            ['code' => 'P-0095', 'name' => 'タップ M8×1.25',         'category' => '切削工具',          'unit_price' =>   950.00, 'unit_name' => '本', 'notes' => 'ハンドタップ'],

            // 外注加工品 - 切削加工品
            ['code' => 'P-0101', 'name' => 'シャフト SH-001',        'category' => '切削加工品',        'unit_price' => 15000.00, 'unit_name' => '本', 'notes' => 'φ25×200mm SUS304 外注品'],
            ['code' => 'P-0102', 'name' => 'フランジ FL-010',        'category' => '切削加工品',        'unit_price' =>  8500.00, 'unit_name' => '個', 'notes' => 'アルミ A2017'],
            ['code' => 'P-0103', 'name' => 'ブラケット BK-020',      'category' => '切削加工品',        'unit_price' => 12000.00, 'unit_name' => '個', 'notes' => 'SUS304 外注品'],

            // 板金加工品
            ['code' => 'P-0111', 'name' => 'カバープレート CP-001',  'category' => '板金加工品',        'unit_price' =>  6000.00, 'unit_name' => '枚', 'notes' => 'SPCC t1.6 粉体塗装'],
            ['code' => 'P-0112', 'name' => 'ボックス筐体 BX-100',    'category' => '板金加工品',        'unit_price' => 18000.00, 'unit_name' => '個', 'notes' => 'アルミ板 200×300×150mm'],

            // 表面処理品
            ['code' => 'P-0121', 'name' => 'アルマイト処理品 AL-001','category' => '表面処理品',        'unit_price' =>  4500.00, 'unit_name' => '個', 'notes' => '黒アルマイト'],
            ['code' => 'P-0122', 'name' => 'ニッケルメッキ品 NI-010','category' => '表面処理品',        'unit_price' =>  3200.00, 'unit_name' => '個', 'notes' => '無電解ニッケル'],
        ];

        foreach ($products as $data) {
            $categoryId = $categoryMap[$data['category']] ?? null;
            if ($categoryId === null) {
                continue;
            }
            Product::firstOrCreate(
                ['code' => $data['code']],
                [
                    'code'        => $data['code'],
                    'name'        => $data['name'],
                    'category_id' => $categoryId,
                    'unit_price'  => $data['unit_price'],
                    'unit_name'   => $data['unit_name'],
                    'notes'       => $data['notes'],
                ]
            );
        }
    }
}
