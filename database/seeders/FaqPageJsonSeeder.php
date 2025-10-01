<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class FaqPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'faq';

        $texts = [
            'page_title' => 'よくあるご質問',
            'page_subtitle' => 'FAQ',
            // Category labels
            'cat_all' => '全て',
            'cat_service' => '各種サービスについて',
            'cat_membership' => '会員について',
            'cat_research' => '調査研究について',
        ];

        // デフォルトFAQ（フロントの静的デフォルトと完全一致）
        $faqs = [
            [
                'category' => 'service',
                'question' => '貴社にとってどんなサービスが提供されますか？',
                'answer' => '私たちは以下のようなサービスを提供しています：
            <ul>
              <li>地域経済に関するリサーチ・分析とレポートの作成をします</li>
              <li>経営戦略立案支援・事業計画策定・プロジェクト管理・経営指導等を実施しています</li>
            </ul>',
                'tags' => []
            ],
            [
                'category' => 'membership',
                'question' => '資料費どのくらいの会費を支払っているのですか？',
                'answer' => '<div class="answer-section">
              <div class="price-info">
                <strong>スタンダード会員</strong><br>
                年会費：12,000円（税別）月額1,000円程度
              </div>
              <div class="benefits">
                <strong>サービス内容：</strong>
                <ul>
                  <li>経済指標・市場分析・企業分析・業界動向・資産・投資・金融等専門経営指導プログラム（入会金別途要）</li>
                </ul>
              </div>
              <div class="price-info premium">
                <strong>プレミアム会員（推奨会員）</strong><br>
                ・ビジネスマッチング：最新の市場分析、事業承継等の支援<br>
                ・M&A（事業承継関連）
              </div>
            </div>',
                'tags' => ['スタンダード会員','プレミアム会員']
            ],
            [
                'category' => 'research',
                'question' => '研究会の開催はありますか？',
                'answer' => '定期的な研究会や勉強会を開催しており、会員の皆様にご参加いただけます。',
                'tags' => []
            ],
            [
                'category' => 'membership',
                'question' => '会費減額、減額はどうしたら良いですか？',
                'answer' => '会費の減額や変更については、お気軽にお問い合わせください。',
                'tags' => []
            ],
            [
                'category' => 'service',
                'question' => '経営診断をしたい場合どうすればいいの、態度を教えてほしい？',
                'answer' => '経営診断をご希望の場合は、まずはお問い合わせフォームよりご連絡ください。',
                'tags' => []
            ],
            [
                'category' => 'service',
                'question' => '料金体系を教えて？',
                'answer' => 'サービスごとに料金体系が異なります。詳しくはお問い合わせください。',
                'tags' => []
            ],
            [
                'category' => 'service',
                'question' => '入会は法人・個人でも申込める？',
                'answer' => '法人・個人を問わずご入会いただけます。',
                'tags' => []
            ],
            [
                'category' => 'service',
                'question' => '入会したい場合の手続きはどうしたらいい？',
                'answer' => '入会手続きについては、お問い合わせフォームよりご連絡いただくか、直接お電話でお問い合わせください。',
                'tags' => []
            ],
            [
                'category' => 'service',
                'question' => '会費はどうやって払う？',
                'answer' => '会費のお支払い方法は、銀行振込、口座振替等をご利用いただけます。',
                'tags' => []
            ],
            [
                'category' => 'service',
                'question' => '会費の支払いはいつする？',
                'answer' => '会費のお支払いタイミングについては、入会時にご案内いたします。',
                'tags' => []
            ],
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'よくあるご質問',
                'content' => [ 'texts' => $texts, 'faqs' => $faqs ],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);

        // 既存のfaqsに対して「質問一致」で上書きマージ（不足は追加）
        $existing = $content['faqs'] ?? [];
        $byQuestion = [];
        foreach ($existing as $item) {
            if (is_array($item) && isset($item['question'])) {
                $byQuestion[$item['question']] = $item;
            }
        }
        foreach ($faqs as $item) {
            $q = $item['question'];
            $byQuestion[$q] = $item; // 上書き（回答の詳細差分を反映）
        }
        $content['faqs'] = array_values($byQuestion);

        $page->update([
            'title' => $page->title ?: 'よくあるご質問',
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}
