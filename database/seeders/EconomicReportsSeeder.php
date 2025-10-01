<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EconomicReport;
use Carbon\Carbon;

class EconomicReportsSeeder extends Seeder
{
    public function run(): void
    {
        $reports = [
            [
                'title' => '地域経済統計レポート',
                'description' => '2024年度版 地域経済動向調査レポート',
                'category' => 'regional',
                'year' => 2024,
                'publication_date' => '2024-04-28',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '地域経済、統計データ、産業分析',
                'cover_image' => '/img/image-1.png',
                'pages' => 85,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1
            ],
            [
                'title' => '四半期経済動向調査レポート（約７）ページ',
                'description' => '2024年第1四半期の経済動向を詳細に分析したレポート',
                'category' => 'quarterly',
                'year' => 2024,
                'publication_date' => '2024-03-31',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '四半期、経済動向、調査',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 2
            ],
            [
                'title' => '年次経済統計データ集（約７）ページ',
                'description' => '2023年度の経済統計データをまとめた年次レポート',
                'category' => 'annual',
                'year' => 2024,
                'publication_date' => '2024-02-28',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '年次統計、経済データ、分析',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 3
            ],
            [
                'title' => '地域経済調査結果報告書（約７）ページ',
                'description' => '地域の産業構造と経済動向に関する詳細調査報告',
                'category' => 'regional',
                'year' => 2024,
                'publication_date' => '2024-01-31',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '地域調査、産業構造、経済分析',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 4
            ],
            [
                'title' => '産業別統計分析レポート（約７）ページ',
                'description' => '主要産業別の統計データと動向分析',
                'category' => 'industry',
                'year' => 2024,
                'publication_date' => '2024-01-15',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '産業統計、業界分析、経済指標',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 5
            ],
            // 2023年のレポート
            [
                'title' => '四半期経済動向調査レポート（約７）ページ',
                'description' => '2023年第4四半期の経済動向を詳細に分析したレポート',
                'category' => 'quarterly',
                'year' => 2023,
                'publication_date' => '2023-12-31',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '四半期、経済動向、調査',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 6
            ],
            [
                'title' => '年次経済統計データ集（約７）ページ',
                'description' => '2022年度の経済統計データをまとめた年次レポート',
                'category' => 'annual',
                'year' => 2023,
                'publication_date' => '2023-11-30',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '年次統計、経済データ、分析',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 7
            ],
            [
                'title' => '地域経済調査結果報告書（約７）ページ',
                'description' => '地域の産業構造と経済動向に関する詳細調査報告',
                'category' => 'regional',
                'year' => 2023,
                'publication_date' => '2023-10-31',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '地域調査、産業構造、経済分析',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 8
            ],
            [
                'title' => '産業別統計分析レポート（約７）ページ',
                'description' => '主要産業別の統計データと動向分析',
                'category' => 'industry',
                'year' => 2023,
                'publication_date' => '2023-09-30',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '産業統計、業界分析、経済指標',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 9
            ],
            // 2022年のレポート
            [
                'title' => '四半期経済動向調査レポート（約７）ページ',
                'description' => '2022年第4四半期の経済動向を詳細に分析したレポート',
                'category' => 'quarterly',
                'year' => 2022,
                'publication_date' => '2022-12-31',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '四半期、経済動向、調査',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 10
            ],
            [
                'title' => '年次経済統計データ集（約７）ページ',
                'description' => '2021年度の経済統計データをまとめた年次レポート',
                'category' => 'annual',
                'year' => 2022,
                'publication_date' => '2022-11-30',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '年次統計、経済データ、分析',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 11
            ],
            [
                'title' => '地域経済調査結果報告書（約７）ページ',
                'description' => '地域の産業構造と経済動向に関する詳細調査報告',
                'category' => 'regional',
                'year' => 2022,
                'publication_date' => '2022-10-31',
                'author' => 'ちくぎん地域経済研究所',
                'publisher' => '株式会社ちくぎん地域経済研究所',
                'keywords' => '地域調査、産業構造、経済分析',
                'cover_image' => '/img/image-1.png',
                'pages' => 7,
                'is_downloadable' => true,
                'members_only' => true,
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 12
            ]
        ];

        foreach ($reports as $report) {
            // タイトルユニーク制約に対応し、再実行時も安全に更新
            EconomicReport::updateOrCreate(
                ['title' => $report['title']],
                $report
            );
        }
    }
}
