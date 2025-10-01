<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class EconomicStatisticsPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'economic-statistics';
        $texts = [
            'page_title' => '経済調査統計一覧',
            'page_subtitle' => 'economic statistics',
            'intro' => '当研究所の経済・調査統計レポートを一覧からご覧いただけます。',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => '経済調査統計一覧',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }
        $content = is_array($page->content) ? $page->content : [];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update(['title' => $page->title ?: '経済調査統計一覧', 'content' => $content]);
    }
}

