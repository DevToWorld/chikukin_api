<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PremiumMembershipPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'premium-membership';
        $alias = 'membership-premium'; // legacy alias key to keep in sync

        $texts = [
            'page_title' => 'プレミアム会員',
            'page_subtitle' => 'premium membership',

            // Section headings/labels
            'benefits_title' => '話題の特典',
            'benefits_label' => 'benefits',

            // Featured content
            'featured_title' => '日経トップリーダービジネスで勝ち抜くヒント集 毎月、日経トップリーダーをお届けします。',
            'featured_body' => '経営トップに役立つ実践的な情報を厳選し、混迷の時代を勝ち抜き、未来を切り開くヒントとなる情報を提供する月刊誌です。毎月、月初に郵送にてお届けします。',
            'featured_cta' => '日経トップリーダーを確認する',

            // CTA buttons
            'cta_primary' => '会員についてお問い合わせ',
            'cta_secondary' => '入会はこちら',
        ];

        // Upsert for primary key
        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'プレミアム会員',
                'content' => [ 'texts' => $texts ],
                'is_published' => true,
                'published_at' => now(),
            ]);
        } else {
            $content = $page->content ?? [];
            if (!is_array($content)) $content = ['html' => (string)$content];
            // Prefer seeder values for premium fields
            $existing = $content['texts'] ?? [];
            $content['texts'] = array_replace((array)$existing, $texts);
            $page->update([
                'title' => $page->title ?: 'プレミアム会員',
                'content' => $content,
                'is_published' => true,
                'published_at' => $page->published_at ?: now(),
            ]);
        }

        // Keep legacy alias in sync to avoid admin/editor confusion
        $aliasPage = PageContent::where('page_key', $alias)->first();
        if (!$aliasPage) {
            PageContent::create([
                'page_key' => $alias,
                'title' => 'プレミアム会員',
                'content' => [ 'texts' => $texts ],
                'is_published' => true,
                'published_at' => now(),
            ]);
        } else {
            $ac = $aliasPage->content ?? [];
            if (!is_array($ac)) $ac = ['html' => (string)$ac];
            $existingAlias = $ac['texts'] ?? [];
            $ac['texts'] = array_replace((array)$existingAlias, $texts);
            $aliasPage->update([
                'title' => $aliasPage->title ?: 'プレミアム会員',
                'content' => $ac,
                'is_published' => true,
                'published_at' => $aliasPage->published_at ?: now(),
            ]);
        }
    }
}
