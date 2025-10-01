<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class FinancialReportsPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'financial-reports';
        $texts = [
            'page_title' => '決算報告',
            'page_subtitle' => 'financial reports',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => '決算報告',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update(['title' => $page->title ?: '決算報告', 'content' => $content]);
    }
}

