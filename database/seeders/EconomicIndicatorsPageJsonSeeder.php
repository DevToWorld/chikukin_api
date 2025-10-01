<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class EconomicIndicatorsPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'economic-indicators';
        $texts = [
            'page_title' => '経済指標一覧',
            'page_subtitle' => 'economic indicators',
            'intro' => '地域・全国の主要経済指標を一覧でご覧いただけます。',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => '経済指標一覧',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }
        $content = is_array($page->content) ? $page->content : [];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update(['title' => $page->title ?: '経済指標一覧', 'content' => $content]);
    }
}

