<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EconomicIndicatorCategory;
use App\Models\EconomicIndicator;

class EconomicIndicatorSeeder extends Seeder
{
    public function run(): void
    {
        // カテゴリーの作成
        $categories = [
            [
                'name' => '景気',
                'slug' => 'keiki',
                'description' => '経済全体の動向を表す指標',
                'color_code' => '#DA5761',
                'sort_order' => 1
            ],
            [
                'name' => '個人消費',
                'slug' => 'kojin-shohi',
                'description' => '個人の消費活動に関する指標',
                'color_code' => '#9C3940',
                'sort_order' => 2
            ],
            [
                'name' => '投資',
                'slug' => 'toshi',
                'description' => '設備投資や住宅投資に関する指標',
                'color_code' => '#1A1A1A',
                'sort_order' => 3
            ],
            [
                'name' => '貿易',
                'slug' => 'boeki',
                'description' => '輸出入に関する指標',
                'color_code' => '#3F3F3F',
                'sort_order' => 4
            ],
            [
                'name' => '生産',
                'slug' => 'seisan',
                'description' => '工業生産に関する指標',
                'color_code' => '#727272',
                'sort_order' => 5
            ],
            [
                'name' => '雇用・所得',
                'slug' => 'koyou-shotoku',
                'description' => '雇用と所得に関する指標',
                'color_code' => '#CFCFCF',
                'sort_order' => 6
            ],
            [
                'name' => '金融',
                'slug' => 'kinyuu',
                'description' => '金融政策と金利に関する指標',
                'color_code' => '#B2B2B2',
                'sort_order' => 7
            ],
            [
                'name' => '海外指標',
                'slug' => 'kaigai-shihyou',
                'description' => '海外の経済指標',
                'color_code' => '#666666',
                'sort_order' => 8
            ]
        ];

        foreach ($categories as $categoryData) {
            EconomicIndicatorCategory::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // 経済指標の作成
        $indicators = [
            // 景気
            [
                'name' => '景気動向指数',
                'category' => 'keiki',
                'description' => '内閣府が発表する景気動向を示す総合的な指標',
                'source' => '内閣府',
                'frequency' => 'monthly',
                'unit' => 'ポイント',
                'link_url' => 'https://www.esri.cao.go.jp/',
                'sort_order' => 1
            ],
            [
                'name' => 'GDP（国内総生産）',
                'category' => 'keiki',
                'description' => '一定期間内に国内で生産された財・サービスの付加価値の総額',
                'source' => '内閣府',
                'frequency' => 'quarterly',
                'unit' => '兆円',
                'link_url' => 'https://www.esri.cao.go.jp/',
                'sort_order' => 2
            ],
            // 個人消費
            [
                'name' => '家計調査',
                'category' => 'kojin-shohi',
                'description' => '家計の収入・支出の状況を調査',
                'source' => '総務省',
                'frequency' => 'monthly',
                'unit' => '円',
                'link_url' => 'https://www.stat.go.jp/',
                'sort_order' => 1
            ],
            [
                'name' => '消費者物価指数',
                'category' => 'kojin-shohi',
                'description' => '消費者が購入する商品・サービスの価格水準を示す指標',
                'source' => '総務省',
                'frequency' => 'monthly',
                'unit' => '%',
                'link_url' => 'https://www.stat.go.jp/',
                'sort_order' => 2
            ],
            // 投資
            [
                'name' => '機械受注',
                'category' => 'toshi',
                'description' => '設備投資の先行指標となる機械受注の動向',
                'source' => '内閣府',
                'frequency' => 'monthly',
                'unit' => '億円',
                'link_url' => 'https://www.esri.cao.go.jp/',
                'sort_order' => 1
            ],
            // 貿易
            [
                'name' => '貿易統計',
                'category' => 'boeki',
                'description' => '輸出入の金額・数量等の統計',
                'source' => '財務省',
                'frequency' => 'monthly',
                'unit' => '億円',
                'link_url' => 'https://www.customs.go.jp/',
                'sort_order' => 1
            ],
            // 生産
            [
                'name' => '鉱工業生産指数',
                'category' => 'seisan',
                'description' => '鉱工業の生産活動の動向を示す指標',
                'source' => '経済産業省',
                'frequency' => 'monthly',
                'unit' => 'ポイント',
                'link_url' => 'https://www.meti.go.jp/',
                'sort_order' => 1
            ],
            // 雇用・所得
            [
                'name' => '完全失業率',
                'category' => 'koyou-shotoku',
                'description' => '労働力人口に占める完全失業者の割合',
                'source' => '総務省',
                'frequency' => 'monthly',
                'unit' => '%',
                'link_url' => 'https://www.stat.go.jp/',
                'sort_order' => 1
            ],
            [
                'name' => '有効求人倍率',
                'category' => 'koyou-shotoku',
                'description' => '求職者1人当たりの求人数',
                'source' => '厚生労働省',
                'frequency' => 'monthly',
                'unit' => '倍',
                'link_url' => 'https://www.mhlw.go.jp/',
                'sort_order' => 2
            ],
            // 金融
            [
                'name' => '政策金利',
                'category' => 'kinyuu',
                'description' => '日本銀行が決定する政策金利',
                'source' => '日本銀行',
                'frequency' => 'monthly',
                'unit' => '%',
                'link_url' => 'https://www.boj.or.jp/',
                'sort_order' => 1
            ],
            // 海外指標
            [
                'name' => 'アメリカGDP',
                'category' => 'kaigai-shihyou',
                'description' => 'アメリカの国内総生産',
                'source' => 'アメリカ商務省',
                'frequency' => 'quarterly',
                'unit' => '%',
                'link_url' => 'https://www.bea.gov/',
                'sort_order' => 1
            ]
        ];

        foreach ($indicators as $indicatorData) {
            EconomicIndicator::firstOrCreate(
                [
                    'name' => $indicatorData['name'],
                    'category' => $indicatorData['category']
                ],
                $indicatorData
            );
        }
    }
}
