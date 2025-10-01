<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class NavigationPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'navigation';
        $texts = [
            // Top-level
            'nav_about' => '私たちについて',
            'nav_seminars' => 'セミナー',
            'nav_publications' => '刊行物',
            'nav_information' => '各種情報',
            'nav_membership' => '入会案内',
            'nav_premium_benefits' => 'プレミアム会員の特典',
            'nav_news' => 'お知らせ',
            'nav_company' => '会社概要',
            'nav_faq' => 'よくある質問',
            // Dropdowns
            'nav_indicators' => '経済指標',
            'nav_statistics' => '経済・調査統計',
            'nav_membership_standard' => 'スタンダード',
            'nav_membership_premium' => 'プレミアム',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'ナビゲーション',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update(['title' => $page->title ?: 'ナビゲーション', 'content' => $content]);
    }
}

