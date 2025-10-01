<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class SitemapPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'sitemap';
        $texts = [
            'page_title' => 'サイトマップ',
            'page_subtitle' => 'SITEMAP',
            'cta_primary' => 'お問い合わせ',
            'cta_secondary' => '入会はこちら',
            'intro' => '当サイトの構造をご案内いたします。',
            // Categories
            'cat_main' => 'メインページ',
            'cat_services' => 'サービス',
            'cat_membership' => '会員サービス',
            'cat_support' => 'お知らせ・サポート',
            'cat_legal' => '法的情報',
            // Main links
            'link_home' => 'トップページ',
            'link_company' => '会社概要',
            'link_about' => '研究所について',
            // Services links
            'link_seminar' => 'セミナー',
            'link_publications' => '刊行物',
            'link_consulting' => '経営コンサルティング',
            'link_research' => '調査・研究',
            'link_training' => '人材育成',
            // Membership
            'link_membership' => '入会案内',
            // Support
            'link_news' => 'お知らせ',
            'link_faq' => 'よくあるご質問',
            'link_contact' => 'お問い合わせ',
            // Legal
            'link_privacy' => 'プライバシーポリシー',
            'link_terms' => '利用規約',
            'link_legal' => '特定商取引法に関する表記',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'サイトマップ',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update(['title' => $page->title ?: 'サイトマップ', 'content' => $content]);
    }
}
