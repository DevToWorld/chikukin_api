<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class MediaPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'media';
        $texts = [
            'page_title' => 'メディアライブラリ',
            'page_subtitle' => 'media library',
            'intro' => 'サイト内で使用する画像やファイルを管理します。',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'メディアライブラリ',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }
        $content = is_array($page->content) ? $page->content : [];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update(['title' => $page->title ?: 'メディアライブラリ', 'content' => $content]);
    }
}

