<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class FooterPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'footer';
        $texts = [
            // Primary links
            'link_home' => 'トップページ',
            'link_company' => '会社概要',
            'link_about' => '私たちについて',
            'link_news' => 'お知らせ',
            'link_faq' => 'よくある質問',
            'link_contact' => 'お問い合わせ',
            // Services
            'link_seminar' => 'セミナー',
            'link_publication' => '刊行物',
            'link_glossary' => '用語集',
            // Membership cluster
            'link_membership' => '入会案内',
            'link_membership_standard' => 'スタンダード',
            'link_membership_premium' => 'プレミアム',
            'link_cri_consulting' => 'CRI 経営コンサルティング',
            // Legal/others
            'link_member_login' => '会員ログイン',
            'link_legal' => '特定商取引法に関する表記',
            'link_privacy' => 'プライバシーポリシー',
            'link_terms' => '利用規約',
            'link_sitemap' => 'サイトマップ',
            // 常設情報（住所・電話・営業時間・著作権）
            'footer_postal_code' => '〒839-0864',
            'footer_address_line' => '福岡県久留米市百年公園1番1号 久留米リサーチセンタービル6階',
            'footer_phone_label' => '電話番号.',
            'footer_phone_value' => '0942-46-5081',
            'footer_hours_label' => '営業時間.',
            'footer_hours_value' => '平日9:00-17:00',
            'footer_copyright' => 'Copyright (C) The Chikugin Research Institute For Regional Economy Co.,Ltd All rights reserved.',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'フッター',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update(['title' => $page->title ?: 'フッター', 'content' => $content]);
    }
}
