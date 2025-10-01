<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsArticle;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => '日銀、金融政策決定会合で現状維持を決定',
                'slug' => 'boj-maintains-policy-2024',
                'summary' => '日本銀行は金融政策決定会合において、現行の金融緩和策を維持することを決定しました。',
                'content' => '日本銀行は本日開催された金融政策決定会合において、大規模な金融緩和策を継続することを全員一致で決定しました。短期金利をマイナス0.1％、長期金利を0％程度に誘導する現在の金融政策を維持します。黒田総裁は記者会見で、物価目標の安定的な達成にはなお時間がかかるとの見解を示しました。',
                'author' => '経済部',
                'category' => '金融政策',
                'tags' => ['日銀', '金融政策', '金利'],
                'view_count' => 1250,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(1),
            ],
            [
                'title' => '2024年第1四半期GDP、前期比0.5％増',
                'slug' => 'gdp-q1-2024-growth',
                'summary' => '内閣府が発表した2024年第1四半期のGDP速報値は、前期比0.5％の成長となりました。',
                'content' => '内閣府が本日発表した2024年1-3月期の国内総生産（GDP）速報値は、物価変動の影響を除いた実質で前期比0.5％増、年率換算で2.0％増となりました。個人消費が堅調に推移したことが主な要因です。設備投資も企業のデジタル化投資を中心に増加傾向を維持しています。',
                'author' => '経済部',
                'category' => '経済指標',
                'tags' => ['GDP', '経済成長', '景気'],
                'view_count' => 890,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => '大手企業、賃上げ率平均5.2％で妥結',
                'slug' => 'wage-increase-2024',
                'summary' => '2024年春季労使交渉において、大手企業の賃上げ率が平均5.2％で妥結しました。',
                'content' => '日本労働組合総連合会（連合）は、2024年春季生活闘争の第3回回答集計結果を発表しました。大手企業を中心とした集計では、賃上げ率が平均5.2％となり、33年ぶりの高水準となりました。物価上昇への対応と人手不足を背景に、企業側が積極的な賃上げに応じた形です。',
                'author' => '社会部',
                'category' => '労働市場',
                'tags' => ['賃金', '春闘', '労働'],
                'view_count' => 2100,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => '円相場、一時1ドル155円台に',
                'slug' => 'yen-weakens-155-2024',
                'summary' => '外国為替市場で円相場が一時1ドル155円台まで下落しました。',
                'content' => '東京外国為替市場で円相場が一時1ドル155円台まで下落しました。日米金利差の拡大を背景に円売りドル買いの動きが加速しています。市場関係者の間では、日銀の金融緩和継続と米連邦準備制度理事会（FRB）の高金利政策の長期化観測が円安要因として指摘されています。',
                'author' => '市場部',
                'category' => '為替市場',
                'tags' => ['円相場', 'ドル円', '為替'],
                'view_count' => 3450,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(4),
            ],
            [
                'title' => 'AI投資ファンド、1000億円規模で設立',
                'slug' => 'ai-investment-fund-launch',
                'summary' => '国内大手金融機関がAI関連企業への投資を目的とした1000億円規模のファンドを設立しました。',
                'content' => '三菱UFJフィナンシャル・グループ、三井住友フィナンシャルグループ、みずほフィナンシャルグループの3メガバンクが共同で、AI関連スタートアップへの投資を目的とした1000億円規模のファンドを設立することを発表しました。日本のAI産業の競争力強化を支援し、グローバル市場での存在感を高めることを目指します。',
                'author' => 'テクノロジー部',
                'category' => '投資',
                'tags' => ['AI', '投資ファンド', 'スタートアップ'],
                'view_count' => 1780,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($articles as $article) {
            // 既存のslugがあれば更新、無ければ作成（再実行時の重複エラーを回避）
            NewsArticle::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}
