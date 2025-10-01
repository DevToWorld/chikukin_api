<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class GlossaryPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'glossary';
        $texts = [
            'page_title' => '用語集',
            'page_subtitle' => 'glossary',
            'download_pdf' => 'PDFダウンロード',
            // ContactSection (used with cms-page-key="glossary")
            'contact_title' => '株式会社ちくぎん地域経済研究所',
            'contact_label' => 'About us',
            'contact_subtitle' => '様々な分野の調査研究を通じ、企業活動などをサポートします。',
            'contact_cta' => 'お問い合わせはこちら',
        ];

        $htmls = [
            'intro' => '経済をより深く知るために必要な用語集となります。',
        ];

        // 初期用語（現行ページの文言に合わせて長文で登録）
        $items = [
            [
                'category' => 'economic',
                'term' => 'CPI',
                'definition' => 'CPIとは、Consumer Price Index（消費者物価指数）の略称で、一般的な消費者が購入する商品やサービスの価格の変動を測る指標です。この指数は、食品や衣類、住宅、交通費など、日常生活に必要な一連の商品とサービスの価格を追跡し、それらの価格が時間とともにどのように変化するかを示します。'
            ],
            [
                'category' => 'economic',
                'term' => 'TIBOR',
                'definition' => 'TIBORとは、Tokyo Interbank Offered Rateの略称で、全国銀行協会が算出する「東京銀行間取引金利」のことを指します。<br><br>具体的には、主要な銀行が他の銀行に資金を貸し出す際に提示する金利の平均値を指します。日本円TIBORとユーロ円TIBORの2種類があり、期間別（1週間、1か月、3か月、6か月、12か月）に算出され、毎営業日午前11時頃に公表されます。<br><br>TIBORは、多くのローンや金融商品の金利設定のベースとして使用されます。例えば、変動金利型住宅ローンの金利や、企業の借入金利などに利用されることがあります。しかし、2012年のLIBOR不正操作問題を受けて、TIBORを含む銀行間取引金利の信頼性や透明性に疑問が投げかけられ、金利指標の改革や代替指標の検討が進められています。その一環で、ユーロ円TIBORは2024年12月末をもって恒久的に公表が停止されています。<br><br>投資家にとっては、TIBORの動向が金融市場全体の金利水準や、金利に連動する金融商品の価格に影響を与えるため、重要な指標の一つとなっています。ただし、近年では代替指標への移行も検討されているため、今後の動向に注意が必要です。'
            ],
            [
                'category' => 'financial',
                'term' => '消費者信頼感指数',
                'definition' => '消費者が将来の経済状況や自身の収入について抱いている期待感や不安感を数値化した指標です。消費者がどの程度消費を控えるか、あるいは積極的に消費を行うかを予測する上で重要な指標となります。'
            ],
            [
                'category' => 'financial',
                'term' => 'GDPデフレーター',
                'definition' => '名目GDPを実質GDPで割って算出される物価指数です。GDPに含まれる全ての財・サービスの価格変動を総合的に測定し、経済全体の物価水準の変化を示す指標です。'
            ],
            [
                'category' => 'business',
                'term' => '短期金融資産',
                'definition' => '1年以内に現金化できる金融資産のことです。現金、預金、短期有価証券、売掛金などが含まれ、企業の流動性や短期的な支払い能力を示す指標となります。'
            ],
            [
                'category' => 'business',
                'term' => 'M&A',
                'definition' => '企業の合併（Merger）と買収（Acquisition）を総称した用語です。企業の成長戦略や事業再編の手段として活用されます。'
            ],
            [
                'category' => 'statistics',
                'term' => '標準偏差',
                'definition' => 'データのばらつきを示す統計指標です。平均値からの偏差の二乗平均の平方根で計算され、リスク分析などに使用されます。'
            ],
            [
                'category' => 'economic',
                'term' => '公定歩合',
                'definition' => '中央銀行が民間銀行に資金を貸し出す際の基準金利です。金融政策の重要な手段として、市場金利や経済活動に大きな影響を与えます。'
            ],
            [
                'category' => 'economic',
                'term' => '実質金利',
                'definition' => '名目金利からインフレ率を差し引いた金利です。実際の購買力の変化を反映し、投資判断や経済分析において重要な指標となります。'
            ],
            [
                'category' => 'financial',
                'term' => '地方債',
                'definition' => '地方公共団体が発行する債券です。道路や学校などの公共事業の財源として活用され、国債と同様に安全な投資対象として知られています。'
            ],
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => '用語集',
                'content' => [
                    'texts' => $texts,
                    'htmls' => $htmls,
                    'items' => $items,
                ],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $content['htmls'] = array_merge($htmls, $content['htmls'] ?? []);

        // items を用語の term でマージ（既存に同じ term があれば今回の定義で更新）
        $existingItems = isset($content['items']) && is_array($content['items']) ? $content['items'] : [];
        $byTerm = [];
        foreach ($existingItems as $it) {
            if (is_array($it) && isset($it['term'])) {
                $byTerm[$it['term']] = $it;
            }
        }
        foreach ($items as $it) {
            $term = $it['term'];
            $byTerm[$term] = $it; // overwrite with longer definition
        }
        $content['items'] = array_values($byTerm);

        $page->update([
            'title' => $page->title ?: '用語集',
            'content' => $content,
        ]);
    }
}
