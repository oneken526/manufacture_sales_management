<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * 得意先サンプルデータを投入する
     */
    public function run(): void
    {
        $customers = [
            [
                'code'         => 'C-0001',
                'name'         => '株式会社東京製作所',
                'name_kana'    => 'カブシキガイシャトウキョウセイサクショ',
                'postal_code'  => '100-0004',
                'address'      => '東京都千代田区大手町1-1-1',
                'phone'        => '03-1111-0001',
                'email'        => 'tokyo@example.com',
                'closing_day'  => 31,
                'credit_limit' => 5000000,
            ],
            [
                'code'         => 'C-0002',
                'name'         => '大阪機械工業株式会社',
                'name_kana'    => 'オオサカキカイコウギョウカブシキガイシャ',
                'postal_code'  => '530-0001',
                'address'      => '大阪府大阪市北区梅田2-2-2',
                'phone'        => '06-2222-0002',
                'email'        => 'osaka@example.com',
                'closing_day'  => 20,
                'credit_limit' => 3000000,
            ],
            [
                'code'         => 'C-0003',
                'name'         => '名古屋精密部品株式会社',
                'name_kana'    => 'ナゴヤセイミツブヒンカブシキガイシャ',
                'postal_code'  => '450-0002',
                'address'      => '愛知県名古屋市中村区名駅3-3-3',
                'phone'        => '052-3333-0003',
                'email'        => 'nagoya@example.com',
                'closing_day'  => 99,
                'credit_limit' => 2000000,
            ],
            [
                'code'         => 'C-0004',
                'name'         => '福岡産業株式会社',
                'name_kana'    => 'フクオカサンギョウカブシキガイシャ',
                'postal_code'  => '812-0012',
                'address'      => '福岡県福岡市博多区博多駅前4-4-4',
                'phone'        => '092-4444-0004',
                'email'        => 'fukuoka@example.com',
                'closing_day'  => 15,
                'credit_limit' => 1000000,
            ],
            [
                'code'         => 'C-0005',
                'name'         => '札幌金属加工株式会社',
                'name_kana'    => 'サッポロキンゾクカコウカブシキガイシャ',
                'postal_code'  => '060-0005',
                'address'      => '北海道札幌市中央区北5条西5-5',
                'phone'        => null,
                'email'        => null,
                'closing_day'  => 99,
                'credit_limit' => 0,
            ],
        ];

        foreach ($customers as $data) {
            Customer::firstOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
