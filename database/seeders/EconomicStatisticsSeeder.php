<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EconomicStatistic;
use Carbon\Carbon;

class EconomicStatisticsSeeder extends Seeder
{
    public function run(): void
    {
        $statistics = [
            [
                'title' => 'GDP成長率 2024年第1四半期',
                'category' => 'GDP',
                'description' => '2024年第1四半期の国内総生産（GDP）成長率',
                'data' => ['前期比' => '0.5%', '前年同期比' => '1.8%'],
                'period_start' => '2024-01-01',
                'period_end' => '2024-03-31',
                'source' => '内閣府',
                'unit' => '%',
                'value' => 1.8,
                'is_published' => true,
            ],
            [
                'title' => '消費者物価指数 2024年4月',
                'category' => 'CPI',
                'description' => '2024年4月の消費者物価指数（総合）',
                'data' => ['総合' => '107.8', 'コア' => '106.5'],
                'period_start' => '2024-04-01',
                'period_end' => '2024-04-30',
                'source' => '総務省',
                'unit' => '指数',
                'value' => 107.8,
                'is_published' => true,
            ],
            [
                'title' => '失業率 2024年4月',
                'category' => '雇用',
                'description' => '2024年4月の完全失業率',
                'data' => ['男性' => '2.7%', '女性' => '2.3%'],
                'period_start' => '2024-04-01',
                'period_end' => '2024-04-30',
                'source' => '総務省',
                'unit' => '%',
                'value' => 2.5,
                'is_published' => true,
            ],
            [
                'title' => '貿易収支 2024年4月',
                'category' => '貿易',
                'description' => '2024年4月の貿易収支',
                'data' => ['輸出' => '8.9兆円', '輸入' => '9.4兆円'],
                'period_start' => '2024-04-01',
                'period_end' => '2024-04-30',
                'source' => '財務省',
                'unit' => '億円',
                'value' => -5000,
                'is_published' => true,
            ],
            [
                'title' => '日経平均株価 月次推移',
                'category' => '金融市場',
                'description' => '2024年4月の日経平均株価月次データ',
                'data' => ['始値' => '40000', '高値' => '41500', '安値' => '39500', '終値' => '40800'],
                'period_start' => '2024-04-01',
                'period_end' => '2024-04-30',
                'source' => '東京証券取引所',
                'unit' => '円',
                'value' => 40800,
                'is_published' => true,
            ],
        ];

        foreach ($statistics as $stat) {
            EconomicStatistic::create($stat);
        }
    }
}