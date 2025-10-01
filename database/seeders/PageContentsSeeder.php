<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentsSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_key' => 'home',
                'title' => 'ホーム',
                'content' => [
                    'hero_title' => '財政・経済のデータプラットフォーム',
                    'hero_subtitle' => '最新の統計データと分析レポートをお届けします',
                    'features' => [
                        [
                            'title' => '経済統計',
                            'description' => '各種経済指標の最新データをリアルタイムで提供',
                        ],
                        [
                            'title' => '財政レポート',
                            'description' => '政府・地方自治体の財政状況を詳細に分析',
                        ],
                        [
                            'title' => 'お知らせ速報',
                            'description' => '経済・財政に関する最新お知らせを即座にお届け',
                        ],
                    ],
                ],
                'meta_description' => '最新の経済統計データと財政分析レポートを提供するプラットフォーム',
                'meta_keywords' => '経済統計, 財政レポート, 経済お知らせ, データ分析',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'page_key' => 'about',
                'title' => '私たちについて',
                'content' => [
                    'mission' => '正確で信頼性の高い経済・財政データを提供し、社会の意思決定を支援します',
                    'history' => '2010年の設立以来、公的機関や研究機関と連携し、データの収集・分析を行っています',
                    'team' => [
                        'economists' => 20,
                        'data_scientists' => 15,
                        'engineers' => 10,
                    ],
                ],
                'meta_description' => '私たちのミッションと歴史について',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'page_key' => 'services',
                'title' => 'サービス',
                'content' => [
                    'data_provision' => [
                        'title' => 'データ提供サービス',
                        'description' => 'APIを通じて最新のデータにアクセス可能',
                    ],
                    'analysis_reports' => [
                        'title' => '分析レポート',
                        'description' => '専門家による詳細な分析レポートを定期配信',
                    ],
                    'consulting' => [
                        'title' => 'コンサルティング',
                        'description' => 'データ活用に関する専門的なアドバイスを提供',
                    ],
                ],
                'meta_description' => '提供するサービスの詳細',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'page_key' => 'contact',
                'title' => 'お問い合わせ',
                'content' => [
                    'email' => 'info@example.com',
                    'phone' => '03-1234-5678',
                    'address' => '東京都千代田区〇〇1-2-3',
                    'business_hours' => '平日 9:00-18:00',
                ],
                'meta_description' => 'お問い合わせ先情報',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'page_key' => 'privacy',
                'title' => 'プライバシーポリシー',
                'content' => [
                    'collection' => '当サービスでは、利用状況の分析のため、Cookieを使用しています',
                    'usage' => '収集した情報は、サービス改善のために使用されます',
                    'protection' => 'お客様の個人情報は適切に保護され、第三者への提供は行いません',
                ],
                'meta_description' => 'プライバシーポリシー',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'page_key' => 'terms',
                'title' => '利用規約',
                'content' => [
                    'agreement' => '本サービスの利用により、以下の規約に同意したものとみなされます',
                    'prohibited' => '不正アクセス、データの無断転載は禁止されています',
                    'disclaimer' => 'データの正確性には万全を期していますが、利用は自己責任でお願いします',
                ],
                'meta_description' => '利用規約',
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($pages as $page) {
            PageContent::create($page);
        }
    }
}